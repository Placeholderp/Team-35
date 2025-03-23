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
use http\Env\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Customer::query()
            ->orderBy("customers.$sortField", $sortDirection)
        ;
        if ($search) {
            $query
                ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                ->join('users', 'customers.user_id', '=', 'users.id')
                ->orWhere('users.email', 'like', "%{$search}%")
                ->orWhere('customers.phone', 'like', "%{$search}%")
            ;
        }

        $paginator = $query->paginate($perPage);

        return CustomerListResource::collection($paginator);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
   

/**
 * Update the specified resource in storage.
 */
/**
 * Update the specified resource in storage.
 */
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
    if ($shippingData) {
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
    if ($billingData) {
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
}
