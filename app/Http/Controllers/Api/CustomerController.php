<?php

namespace App\Http\Controllers\Api;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\CustomerActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Extract and sanitize pagination and filter parameters
            $perPage = max(1, intval($request->input('per_page', 10)));
            $search = trim($request->input('search', ''));
            $sortField = $this->validateSortField($request->input('sort_field', 'updated_at'));
            $sortDirection = strtolower($request->input('sort_direction', 'desc')) === 'asc' ? 'asc' : 'desc';
            
            // Clean up status parameter - remove any non-numeric characters
            $rawStatus = $request->input('status', '');
            $status = preg_replace('/[^0-9]/', '', $rawStatus);
            
            // Log the received and sanitized parameters for debugging
            Log::info('Customer index parameters', [
                'raw_params' => $request->all(),
                'sanitized' => [
                    'per_page' => $perPage,
                    'search' => $search,
                    'sort_field' => $sortField,
                    'sort_direction' => $sortDirection,
                    'status' => $status,
                    'raw_status' => $rawStatus
                ]
            ]);
            
            // Start building the query
            $query = Customer::query();
            
            // Handle search before anything else to properly set up joins
            if (!empty($search)) {
                // Join with users table for email search - use left join to keep all customers
                $query->leftJoin('users', 'customers.user_id', '=', 'users.id')
                      ->where(function($q) use ($search) {
                          $q->where(DB::raw("CONCAT(customers.first_name, ' ', customers.last_name)"), 'like', "%{$search}%")
                            ->orWhere('users.email', 'like', "%{$search}%")
                            ->orWhere('customers.phone', 'like', "%{$search}%");
                      })
                      ->select('customers.*'); // Make sure we only get customer fields
            }
            
            // Add status filter if provided and valid
            if ($status !== '') {
                $query->where('customers.status', (int)$status);
            }
            
            // Handle sorting - special case for email which is in users table
            if ($sortField === 'email' && !$query->getQuery()->joins) {
                // Only join if we haven't already joined for search
                $query->leftJoin('users', 'customers.user_id', '=', 'users.id')
                      ->select('customers.*');
            }
            
            // Apply the sorting
            if ($sortField === 'email') {
                // Email field is in users table
                $query->orderBy("users.email", $sortDirection);
            } else if ($sortField === 'id') {
                // If sorting by ID, use user_id instead since that's our primary key
                $query->orderBy('customers.user_id', $sortDirection);
            } else {
                // Otherwise sort by the requested field on customers table
                $query->orderBy("customers.$sortField", $sortDirection);
            }
            
            // Make sure we have distinct results if joins were used
            if ($query->getQuery()->joins) {
                $query->distinct();
            }
            
            // Execute the query with pagination
            $paginator = $query->paginate($perPage);
            
            // Transform the data using the resource collection
            return CustomerListResource::collection($paginator);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in customer index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Return a 500 error with details
            return response()->json([
                'message' => 'An error occurred while retrieving customers.',
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        try {
            $customerData = $request->validated();
            $customerData['created_by'] = $request->user()->id;
            
            // Convert boolean status to integer (1 for active, 0 for disabled)
            // This ensures compatibility with the database's integer column
            $customerData['status'] = isset($customerData['status']) ? ($customerData['status'] ? 1 : 0) : 1;
            
            // Extract address data
            $shippingData = $customerData['shippingAddress'] ?? null;
            $billingData = $customerData['billingAddress'] ?? null;
            
            // Remove the address data from the customer model creation
            unset($customerData['shippingAddress'], $customerData['billingAddress']);
            
            // Create customer
            $customer = Customer::create($customerData);
            
            // Handle shipping address
            if ($shippingData && !empty($shippingData['address1'])) {
                // Call the prepareAddressData method to ensure no NULL values
                $this->prepareAddressData($shippingData);
                
                $shippingData['user_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                
                // Create new shipping address
                CustomerAddress::create($shippingData);
            }
            
            // Handle billing address
            if ($billingData && !empty($billingData['address1'])) {
                // Call the prepareAddressData method to ensure no NULL values
                $this->prepareAddressData($billingData);
                
                $billingData['user_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                
                // Create new billing address
                CustomerAddress::create($billingData);
            }
            
            return new CustomerResource($customer);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in customer store: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Return a 500 error with details
            return response()->json([
                'message' => 'An error occurred while creating the customer.',
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    /**
     * Validate sort field to prevent SQL injection
     */
    private function validateSortField($field)
    {
        $allowedFields = [
            'user_id', 'id', 'first_name', 'last_name', 'email', 
            'status', 'created_at', 'updated_at'
        ];
        
        return in_array($field, $allowedFields) ? $field : 'updated_at';
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Ensure address data fields are not NULL.
     * 
     * @param array &$addressData
     * @return void
     */
    private function prepareAddressData(&$addressData)
    {
        // Define the fields that should not be NULL
        $fields = [
            'address1', 'address2', 'city', 'state', 'zipcode', 'country_code'
        ];
        
        foreach ($fields as $field) {
            // If the field is NULL or not set, default to empty string
            if (!isset($addressData[$field]) || $addressData[$field] === null) {
                $addressData[$field] = '';
            }
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customerData = $request->validated();
        $customerData['updated_by'] = $request->user()->id;
        
        // Convert boolean status to integer (1 for active, 0 for disabled)
        // This ensures compatibility with the database's integer column
        $customerData['status'] = $customerData['status'] ? 1 : 0;
        
        // Extract address data
        $shippingData = $customerData['shippingAddress'] ?? null;
        $billingData = $customerData['billingAddress'] ?? null;
        
        // Remove the address data from the customer model update
        unset($customerData['shippingAddress'], $customerData['billingAddress']);

        // Update customer
        $customer->update($customerData);

        // Handle shipping address
        if ($shippingData && !empty($shippingData['address1'])) {
            // Call the prepareAddressData method to ensure no NULL values
            $this->prepareAddressData($shippingData);
            
            $shippingData['user_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            
            if ($customer->shippingAddress) {
                // Update existing shipping address
                $customer->shippingAddress->update($shippingData);
            } else {
                // Create new shipping address
                CustomerAddress::create($shippingData);
            }
        }
        
        // Handle billing address
        if ($billingData && !empty($billingData['address1'])) {
            // Call the prepareAddressData method to ensure no NULL values
            $this->prepareAddressData($billingData);
            
            $billingData['user_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            
            if ($customer->billingAddress) {
                // Update existing billing address
                $customer->billingAddress->update($billingData);
            } else {
                // Create new billing address
                CustomerAddress::create($billingData);
            }
        }

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->noContent();
    }

    public function countries()
    {
        return CountryResource::collection(Country::query()->orderBy('name', 'asc')->get());
    }

    /**
     * Get average customer value
     */
    public function getAverageValue()
    {
        // Calculate average order value per customer
        $avgValue = Order::select('customer_id', DB::raw('AVG(total) as average_value'))
            ->groupBy('customer_id')
            ->avg('average_value');
            
        return response()->json([
            'average_value' => round($avgValue ?? 0, 2)
        ]);
    }

    /**
     * Get customer retention rate
     */
    public function getRetentionRate()
    {
        // Count total customers
        $totalCustomers = Customer::count();
        
        // Count customers with more than one order
        $repeatCustomers = DB::table('orders')
            ->select('customer_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('customer_id')
            ->having('order_count', '>', 1)
            ->count();
            
        $retentionRate = $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;
        
        return response()->json([
            'retention_rate' => round($retentionRate, 1)
        ]);
    }

    /**
     * Get customer activity data for dashboard
     */
}