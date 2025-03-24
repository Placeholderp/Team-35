<?php

namespace App\Http\Controllers\Api;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\OrderResource;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Traits\ReportTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ReportTrait;

    /**
     * Get the count of active customers.
     *
     * @param Request $request
     * @return int
     */
    public function activeCustomers(Request $request)
    {
        $dateRange = $this->getDateRange($request->get('d', 'all'));
        $query = Customer::where('status', CustomerStatus::Active->value);

        if ($dateRange['start']) {
            $query->where('created_at', '>=', $dateRange['start'])
                  ->where('created_at', '<=', $dateRange['end']);
        }

        return $query->count();
    }

    /**
     * Get the count of active (published) products.
     *
     * @param Request $request
     * @return int
     */
    public function activeProducts(Request $request)
    {
        $dateRange = $this->getDateRange($request->get('d', 'all'));
        $query = Product::where('published', '=', 1);

        if ($dateRange['start']) {
            $query->where('created_at', '>=', $dateRange['start'])
                  ->where('created_at', '<=', $dateRange['end']);
        }

        return $query->count();
    }

    /**
     * Get the count of paid orders.
     *
     * @param Request $request
     * @return int
     */
    public function paidOrders(Request $request)
    {
        $dateRange = $this->getDateRange($request->get('d', 'all'));
        $fromDate = $this->getFromDate();
        $query = Order::query()->where('status', OrderStatus::Paid->value);

        if ($dateRange['start']) {
            $query->where('created_at', '>=', $dateRange['start'])
                  ->where('created_at', '<=', $dateRange['end']);
        } else if ($fromDate) {
            // Fall back to the legacy from date if no specific date range is provided
            $query->where('created_at', '>', $fromDate);
        }

        return $query->count();
    }

    /**
     * Calculate the total income from paid orders.
     *
     * @param Request $request
     * @return float
     */
    public function totalIncome(Request $request)
    {
        $dateRange = $this->getDateRange($request->get('d', 'all'));
        $fromDate = $this->getFromDate();
        $query = Order::query()->where('status', OrderStatus::Paid->value);

        if ($dateRange['start']) {
            $query->where('created_at', '>=', $dateRange['start'])
                  ->where('created_at', '<=', $dateRange['end']);
        } else if ($fromDate) {
            // Fall back to the legacy from date if no specific date range is provided
            $query->where('created_at', '>', $fromDate);
        }

        // Sum up the total_price from all matching orders and round the result.
        return round($query->sum('total_price'));
    }

    /**
     * Get a list of orders grouped by billing country.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ordersByCountry(Request $request)
    {
        $dateRange = $this->getDateRange($request->get('d', 'all'));
        $fromDate = $this->getFromDate();
        
        $query = Order::query()
            ->select(['c.name', DB::raw('count(orders.id) as count')])
            ->join('users', 'created_by', '=', 'users.id')
            ->join('customer_addresses AS a', 'users.id', '=', 'a.customer_id')
            ->join('countries AS c', 'a.country_code', '=', 'c.code')
            ->where('status', OrderStatus::Paid->value)
            ->where('a.type', AddressType::Billing->value)
            ->groupBy('c.name');

        if ($dateRange['start']) {
            $query->where('orders.created_at', '>=', $dateRange['start'])
                  ->where('orders.created_at', '<=', $dateRange['end']);
        } else if ($fromDate) {
            $query->where('orders.created_at', '>', $fromDate);
        }

        return $query->get();
    }

    /**
     * Get the latest active customers.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function latestCustomers(Request $request)
    {
        $dateRange = $this->getDateRange($request->get('d', 'all'));
        $query = Customer::query()
            ->select(['user_id as id', 'first_name', 'last_name', 'u.email', 'phone', 'u.created_at'])
            // Join with users using the customer's user_id.
            ->join('users AS u', 'u.id', '=', 'customers.user_id')
            ->where('status', CustomerStatus::Active->value);

        if ($dateRange['start']) {
            $query->where('u.created_at', '>=', $dateRange['start'])
                  ->where('u.created_at', '<=', $dateRange['end']);
        }

        $customers = $query
            ->orderBy('u.created_at', 'desc')
            ->limit(5)
            ->get();

        // Format customers data for the dashboard
        foreach ($customers as $customer) {
            // Add orders_count
            $customer->orders_count = Order::where('created_by', $customer->id)->count();
            // Add avatar flag (could be based on profile image existence)
            $customer->avatar = false;
        }

        return $customers;
    }

    /**
     * Get the latest paid orders.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function latestOrders(Request $request)
    {
        $dateRange = $this->getDateRange($request->get('d', 'all'));
        $query = Order::query()
            ->select([
                'o.id',
                'o.total_price',
                'o.status',
                'o.created_at',
                DB::raw('COUNT(oi.id) AS items'),
                'c.user_id',
                'c.first_name',
                'c.last_name'
            ])
            ->from('orders AS o')
            ->join('order_items AS oi', 'oi.order_id', '=', 'o.id')
            ->join('customers AS c', 'c.user_id', '=', 'o.created_by')
            ->where('o.status', OrderStatus::Paid->value);

        if ($dateRange['start']) {
            $query->where('o.created_at', '>=', $dateRange['start'])
                  ->where('o.created_at', '<=', $dateRange['end']);
        }

        $orders = $query
            ->limit(10)
            ->orderBy('o.created_at', 'desc')
            ->groupBy('o.id', 'o.total_price', 'o.status', 'o.created_at', 'c.user_id', 'c.first_name', 'c.last_name')
            ->get();

        // Format orders for the dashboard
        foreach ($orders as $order) {
            // Format created_at date
            $order->created_at = Carbon::parse($order->created_at)->format('M d, Y');
        }

        return response()->json(['data' => $orders]);
    }
    /**
 * Get revenue breakdown by product category.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function revenueBreakdown(Request $request)
{
    $dateRange = $this->getDateRange($request->get('d', 'all'));
    
    $query = DB::table('order_items AS oi')
        ->join('orders AS o', 'oi.order_id', '=', 'o.id')
        ->join('products AS p', 'oi.product_id', '=', 'p.id')
        ->join('categories AS c', 'p.category_id', '=', 'c.id')
        ->select(
            'c.name as category_name',
            DB::raw('SUM(oi.quantity * oi.unit_price) as revenue_amount')
        )
        ->where('o.status', OrderStatus::Paid->value);
    
    if ($dateRange['start']) {
        $query->where('o.created_at', '>=', $dateRange['start'])
              ->where('o.created_at', '<=', $dateRange['end']);
    }
    
    $revenues = $query->groupBy('c.id', 'c.name')
        ->orderBy('revenue_amount', 'desc')
        ->limit(6)
        ->get();
        
    return response()->json($revenues);
}

/**
 * Get trend data for dashboard charts.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function trendData(Request $request)
{
    $dateRange = $this->getDateRange($request->get('d', 'all'));
    $period = $this->getPeriodByDateRange($request->get('d', 'all'));
    
    // For simple trends, we'll use 7 data points
    $points = 7;
    
    // Get customer trend (new customer signups)
    $customerTrend = [];
    $query = User::where('is_admin', 0);
    
    if ($dateRange['start']) {
        $query->where('created_at', '>=', $dateRange['start'])
              ->where('created_at', '<=', $dateRange['end']);
    }
    
    // Get the count of new customers over the last 7 days/weeks
    for ($i = $points - 1; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        $customerTrend[] = mt_rand(3, 15); // Placeholder - replace with actual data
    }
    
    // Get product trend (top product sales)
    $productTrend = [];
    for ($i = 0; $i < $points; $i++) {
        $productTrend[] = mt_rand(5, 30); // Placeholder - replace with actual data
    }
    
    // Get order trend (orders per day)
    $orderTrend = [];
    for ($i = 0; $i < $points; $i++) {
        $orderTrend[] = mt_rand(2, 25); // Placeholder - replace with actual data
    }
    
    // Get revenue trend
    $revenueTrend = [];
    for ($i = 0; $i < $points; $i++) {
        $revenueTrend[] = mt_rand(1000, 5000); // Placeholder - replace with actual data
    }
    
    return response()->json([
        'customers' => $customerTrend,
        'products' => $productTrend,
        'orders' => $orderTrend,
        'revenue' => $revenueTrend
    ]);
}

/**
 * Get performance insight for the dashboard.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function performanceInsight(Request $request)
{
    $dateRange = $this->getDateRange($request->get('d', 'all'));
    
    // Find the top performing category
    $topCategory = DB::table('order_items AS oi')
        ->join('orders AS o', 'oi.order_id', '=', 'o.id')
        ->join('products AS p', 'oi.product_id', '=', 'p.id')
        ->join('categories AS c', 'p.category_id', '=', 'c.id')
        ->select(
            'c.name as category_name',
            DB::raw('SUM(oi.quantity * oi.unit_price) as revenue_amount')
        )
        ->where('o.status', OrderStatus::Paid->value);
    
    if ($dateRange['start']) {
        $topCategory->where('o.created_at', '>=', $dateRange['start'])
                  ->where('o.created_at', '<=', $dateRange['end']);
    }
    
    $topCategory = $topCategory->groupBy('c.id', 'c.name')
        ->orderBy('revenue_amount', 'desc')
        ->first();
    
    // Generate a relevant insight
    $insight = "Your fitness store is ready to track sales performance.";
    
    if ($topCategory) {
        // If we have data, provide more detailed insights
        $growthPercentage = mt_rand(8, 25); // Placeholder - calculate actual growth
        
        $insights = [
            "Your top-selling category \"" . $topCategory->category_name . "\" has generated the most revenue. Overall sales have increased by {$growthPercentage}% compared to the previous period.",
            "\"" . $topCategory->category_name . "\" continues to be your best-performing category with strong customer demand.",
            "Customers are showing strong interest in your \"" . $topCategory->category_name . "\" category. Consider expanding this product line.",
        ];
        
        $insight = $insights[array_rand($insights)];
    }
    
    return response()->json(['message' => $insight]);
}

/**
 * Helper method to determine period segments
 *
 * @param string $period
 * @return string
 */
private function getPeriodByDateRange($period)
{
    switch ($period) {
        case '1d':
            return 'hour';
        case '1k':
        case '2k':
            return 'day';
        case '1m':
            return 'day';
        case '3m':
        case '6m':
            return 'week';
        case 'all':
        default:
            return 'month';
    }
}

    /**
     * Get date range based on period parameter
     *
     * @param string $period
     * @return array
     */
    private function getDateRange($period = 'all')
    {
        $end = Carbon::now();
        $start = null;
        
        switch ($period) {
            case '1d':
                $start = Carbon::now()->subDay();
                break;
            case '1k':
                $start = Carbon::now()->subWeek();
                break;
            case '2k':
                $start = Carbon::now()->subWeeks(2);
                break;
            case '1m':
                $start = Carbon::now()->subMonth();
                break;
            case '3m':
                $start = Carbon::now()->subMonths(3);
                break;
            case '6m':
                $start = Carbon::now()->subMonths(6);
                break;
            case 'all':
            default:
                // No start date filter
                $start = null;
        }
        
        return [
            'start' => $start,
            'end' => $end
        ];
    }
}