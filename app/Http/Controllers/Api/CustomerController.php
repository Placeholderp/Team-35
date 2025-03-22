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
    public function update(CustomerRequest $request, Customer $customer)
{
    $customerData = $request->validated();
    $customerData['updated_by'] = $request->user()->id;
    $customerData['status'] = $customerData['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
    
    // Use null coalescing operator to handle missing address data
    $shippingData = $customerData['shippingAddress'] ?? null;
    $billingData = $customerData['billingAddress'] ?? null;

    $customer->update($customerData);

    // Only process shipping address if it exists
    if ($shippingData && $customer->shippingAddress) {
        $customer->shippingAddress->update($shippingData);
    } else if ($shippingData) {
        $shippingData['customer_id'] = $customer->user_id;
        $shippingData['type'] = AddressType::Shipping->value;
        CustomerAddress::create($shippingData);
    }
    
    // Only process billing address if it exists
    if ($billingData && $customer->billingAddress) {
        $customer->billingAddress->update($billingData);
    } else if ($billingData) {
        $billingData['customer_id'] = $customer->user_id;
        $billingData['type'] = AddressType::Billing->value;
        CustomerAddress::create($billingData);
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
