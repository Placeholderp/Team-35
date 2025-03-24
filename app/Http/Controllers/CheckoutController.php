<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Get user's saved addresses to pre-populate the form if they exist
        $user = $request->user();
        $billingAddress = null;
        $customer = null;
        
        if ($user) {
            // Get customer record
            $customer = Customer::where('user_id', $user->id)->first();
            
            if ($customer) {
                $billingAddress = CustomerAddress::where('user_id', $user->id)
                    ->where('type', AddressType::Billing->value)
                    ->first();
            }
        }
        
        return view('checkout', compact('billingAddress', 'customer'));
    }

    public function process(Request $request)
    {
        // Enable DEBUG mode for detailed logs
        Log::info('CHECKOUT DEBUG: Starting checkout process', ['user_id' => Auth::id()]);
        
        // Add logging to see if cart data is coming through from localStorage
        $cartData = $request->input('cart_data');
        Log::info('CHECKOUT DEBUG: Cart data received', ['cart_data' => $cartData]);
        
        // Validate input - form field names
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string|max:255',  // Form field name is "address"
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:45',     // Form field name is "zip"
            'country' => 'required|string|max:3',  // Form field name is "country"
            'state' => 'nullable|string|max:45',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string|in:credit_card,paypal',
            'cc_name' => 'required_if:payment_method,credit_card|string|max:255',
            'cc_number' => 'required_if:payment_method,credit_card|string',
            'cc_expiration' => 'required_if:payment_method,credit_card|string',
            'cc_cvv' => 'required_if:payment_method,credit_card|string',
        ]);
        
        Log::info('CHECKOUT DEBUG: Form data validated', ['fields' => array_keys($validatedData)]);
        
        // Start a database transaction to ensure data integrity
        DB::beginTransaction();
        
        try {
            // Get the authenticated user
            $user = $request->user();
            if (!$user) {
                throw new \Exception('User is not authenticated');
            }
            
            Log::info('CHECKOUT DEBUG: User retrieved', ['user_id' => $user->id]);
            
            // If localStorage cart data is present, sync it with the server-side cart
            if (!empty($cartData)) {
                $this->syncLocalStorageCart($user, $cartData);
                Log::info('CHECKOUT DEBUG: localStorage cart synced with server');
            }
            
            // Process the order (create order and order items)
            $order = $this->checkout($request);
            Log::info('CHECKOUT DEBUG: Order created', ['order_id' => $order->id]);
            
            // Now save customer information - using direct SQL for reliability
            $this->saveCustomerInfo($user, $validatedData);
            Log::info('CHECKOUT DEBUG: Customer info saved');
            
            // Now save order details - also using direct SQL
            $this->saveOrderDetails($order, $user, $validatedData);
            Log::info('CHECKOUT DEBUG: Order details saved');
            
            // If everything goes well, commit the transaction
            DB::commit();
            Log::info('CHECKOUT DEBUG: Transaction committed successfully');
            
            return redirect()->route('success')->with('clear_cart', true);
        } catch (\Exception $e) {
            // If something goes wrong, rollback the transaction
            DB::rollBack();
            
            // Log the error
            Log::error('CHECKOUT DEBUG: Error in checkout process', [
                'user_id' => $request->user()->id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return back with error message
            return back()->withErrors(['error' => 'There was an error processing your order: ' . $e->getMessage()])
                        ->withInput();
        }
    }
    
    /**
     * Synchronize localStorage cart with server-side cart
     */
    private function syncLocalStorageCart($user, $cartData)
    {
        try {
            $cartItems = json_decode($cartData, true);
            
            if (empty($cartItems) || !is_array($cartItems)) {
                Log::warning('CHECKOUT DEBUG: Invalid cart data format', ['cart_data' => $cartData]);
                return;
            }
            
            // Clear existing cart items first to avoid duplicates
            CartItem::where('user_id', $user->id)->delete();
            
            foreach ($cartItems as $item) {
                // Validate product exists
                $product = Product::find($item['id']);
                if (!$product) {
                    Log::warning('CHECKOUT DEBUG: Product not found', ['product_id' => $item['id']]);
                    continue;
                }
                
                // Create or update cart item
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
                
                Log::info('CHECKOUT DEBUG: Added item to cart', [
                    'product_id' => $item['id'], 
                    'quantity' => $item['quantity']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('CHECKOUT DEBUG: Error syncing localStorage cart', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Save customer information and addresses using direct SQL
     * This bypasses any potential Eloquent model issues
     */
    private function saveCustomerInfo($user, array $data)
    {
        // First, ensure customer record exists
        $customer = Customer::where('user_id', $user->id)->first();
        
        $validCountryCodes = DB::table('countries')->pluck('code')->toArray();
        if (!in_array($data['country'], $validCountryCodes)) {
            // Use GB instead of UK since we saw both exist but UK is causing issues
            $data['country'] = 'GB';
        }
        
        if (!$customer) {
            // Create a new customer record
            Customer::create([
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'status' => CustomerStatus::Active->value
            ]);
            
            Log::info('CHECKOUT DEBUG: Created new customer record');
        } else {
            // Update existing customer
            $customer->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email']
            ]);
            
            Log::info('CHECKOUT DEBUG: Updated existing customer');
        }
        
        // Use INSERT ON DUPLICATE KEY UPDATE for billing address
        try {
            DB::statement("
                INSERT INTO customer_addresses 
                (user_id, type, address1, address2, city, state, zipcode, country_code, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                address1 = VALUES(address1),
                address2 = VALUES(address2),
                city = VALUES(city),
                state = VALUES(state), 
                zipcode = VALUES(zipcode),
                country_code = VALUES(country_code),
                updated_at = NOW()
            ", [
                $user->id, 
                AddressType::Billing->value, 
                $data['address'], 
                $data['address2'] ?? '', 
                $data['city'], 
                $data['state'] ?? '', 
                $data['zip'], 
                $data['country']
            ]);
            
            Log::info('CHECKOUT DEBUG: Billing address saved using INSERT ON DUPLICATE KEY UPDATE');
        } catch (\Exception $e) {
            Log::error('CHECKOUT DEBUG: Error saving billing address', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            throw $e;
        }
        
        // Use INSERT ON DUPLICATE KEY UPDATE for shipping address
        try {
            DB::statement("
                INSERT INTO customer_addresses 
                (user_id, type, address1, address2, city, state, zipcode, country_code, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                address1 = VALUES(address1),
                address2 = VALUES(address2),
                city = VALUES(city),
                state = VALUES(state), 
                zipcode = VALUES(zipcode),
                country_code = VALUES(country_code),
                updated_at = NOW()
            ", [
                $user->id, 
                AddressType::Shipping->value, 
                $data['address'], 
                $data['address2'] ?? '', 
                $data['city'], 
                $data['state'] ?? '', 
                $data['zip'], 
                $data['country']
            ]);
            
            Log::info('CHECKOUT DEBUG: Shipping address saved using INSERT ON DUPLICATE KEY UPDATE');
        } catch (\Exception $e) {
            Log::error('CHECKOUT DEBUG: Error saving shipping address', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            throw $e;
        }
    }
    
    /**
     * Save order details using direct SQL
     */
    private function saveOrderDetails($order, $user, array $data)
    {
        try {
            // Use direct INSERT to avoid any model issues
            DB::statement("
                INSERT INTO order_details (
                    order_id, first_name, last_name, 
                    address1, address2, city, state, 
                    zipcode, country_code, created_at, updated_at
                ) VALUES (
                    ?, ?, ?, 
                    ?, ?, ?, ?, 
                    ?, ?, NOW(), NOW()
                )
            ", [
                $order->id,
                $data['first_name'],
                $data['last_name'],
                $data['address'],
                $data['address2'] ?? '',
                $data['city'],
                $data['state'] ?? '',
                $data['zip'],
                $data['country']
            ]);
            
            Log::info('CHECKOUT DEBUG: Order detail inserted successfully');
            
            // Verify it was actually inserted
            $verify = DB::select('SELECT * FROM order_details WHERE order_id = ?', [$order->id]);
            if (count($verify) > 0) {
                Log::info('CHECKOUT DEBUG: Verified order detail exists');
            } else {
                Log::warning('CHECKOUT DEBUG: Could not verify order detail!');
            }
        } catch (\Exception $e) {
            Log::error('CHECKOUT DEBUG: Error inserting order detail', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            throw $e;
        }
    }

    /**
     * Create the order and order items
     */
    public function checkout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'unit_price' => $product->price,
            ];
        }

        // Create Order
        $orderData = [
            'total_price' => $totalPrice,
            'status'      => OrderStatus::Pending->value,
            'created_by'  => $user->id,
            'updated_by'  => $user->id,
        ];
        $order = Order::create($orderData);
// Create Order Items
foreach ($orderItems as $orderItem) {
    $orderItem['order_id'] = $order->id;
    OrderItem::create($orderItem);
    
    // ADD YOUR NEW CODE RIGHT HERE
    // Update product inventory
    $product = Product::find($orderItem['product_id']);
    if ($product && $product->track_inventory) {
        $product->quantity -= $orderItem['quantity'];
        $product->save();
        Log::info('CHECKOUT DEBUG: Updated product inventory', [
            'product_id' => $product->id,
            'old_quantity' => $product->quantity + $orderItem['quantity'],
            'new_quantity' => $product->quantity
        ]);
    }
}
        // Create Payment record
        $paymentType = $request->input('payment_method') === 'paypal' ? 'paypal' : 'cc';
        Payment::create([
            'order_id'   => $order->id,
            'amount'     => $totalPrice,
            'status'     => PaymentStatus::Pending->value,
            'type'       => $paymentType,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // Clear the user's cart items
        CartItem::where(['user_id' => $user->id])->delete();

        return $order;
    }

    public function success(Request $request)
    {
        // Pass flag to clear localStorage cart
        return view('success')->with('clear_cart', true);
    }
}