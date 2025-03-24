<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Traits\ReportTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ReportTrait;

    /**
     * Get summary data for dashboard
     */
    public function summary(Request $request)
    {
        $dateRange = $request->query('date_range', 'all');
        $fromDate = $this->getFromDate($dateRange);
        $prevFromDate = $this->getPreviousPeriodDate($dateRange);
        
        // Get current period order stats
        $orderStats = Order::where('created_at', '>=', $fromDate)
            ->selectRaw('COUNT(*) as total, AVG(total_price) as average')
            ->first();
            
        // Get previous period order stats
        $prevOrderStats = Order::where('created_at', '>=', $prevFromDate)
            ->where('created_at', '<', $fromDate)
            ->selectRaw('COUNT(*) as total')
            ->first();
            
        // Get customer stats
        $customerStats = Customer::where('created_at', '>=', $fromDate)
            ->count();
            
        $prevCustomerStats = Customer::where('created_at', '>=', $prevFromDate)
            ->where('created_at', '<', $fromDate)
            ->count();
            
        // Calculate comparison percentages
        $orderComparison = $this->calculatePercentChange(
            $orderStats->total, 
            $prevOrderStats->total
        );
        
        $customerComparison = $this->calculatePercentChange(
            $customerStats, 
            $prevCustomerStats
        );
        
        return response()->json([
            'orders' => [
                'total' => (int)$orderStats->total,
                'average' => round($orderStats->average, 2),
                'comparison' => $orderComparison
            ],
            'customers' => [
                'total' => $customerStats,
                'growth' => $customerComparison,
                'comparison' => $customerComparison
            ]
        ]);
    }

    /**
     * Generate a detailed report for orders.
     */
    public function ordersReport(Request $request)
    {
        $dateRange = $request->query('date_range', 'all');
        $groupBy = $request->query('group_by', 'day');
        
        $fromDate = $this->getFromDate($dateRange);
        $prevFromDate = $this->getPreviousPeriodDate($dateRange);
        
        // Get order metrics
        $metrics = $this->getOrderMetrics($fromDate, $prevFromDate);
        
        // Get order segments
        $segments = $this->getOrderSegments($fromDate);
        
        // Get top products
        $topProducts = $this->getTopProducts($fromDate);
        
        // Get chart data
        $chartData = $this->getOrderChartData($fromDate, $groupBy);
        
        return response()->json([
            'metrics' => $metrics,
            'segments' => $segments,
            'topProducts' => $topProducts,
            'chartData' => $chartData
        ]);
    }

    /**
     * Generate a detailed report for customers.
     */
    public function customersReport(Request $request)
    {
        $dateRange = $request->query('date_range', 'all');
        $groupBy = $request->query('group_by', 'day');
        
        $fromDate = $this->getFromDate($dateRange);
        $prevFromDate = $this->getPreviousPeriodDate($dateRange);
        
        // Get customer metrics
        $metrics = $this->getCustomerMetrics($fromDate, $prevFromDate);
        
        // Get customer segments
        $segments = $this->getCustomerSegments($fromDate);
        
        // Get chart data
        $chartData = $this->getCustomerChartData($fromDate, $groupBy);
        
        return response()->json([
            'metrics' => $metrics,
            'segments' => $segments,
            'chartData' => $chartData
        ]);
    }

    /**
     * Export orders data to CSV
     */
    public function exportOrders(Request $request)
    {
        $dateRange = $request->query('date_range', 'all');
        $fromDate = $this->getFromDate($dateRange);
        
        $orders = Order::with('customer')
            ->where('created_at', '>=', $fromDate)
            ->get();
            
        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_report.csv"',
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Order ID', 'Customer', 'Amount', 'Date', 'Status']);
            
            // Add data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'N/A',
                    $order->total_price,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->status
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export customers data to CSV
     */
    public function exportCustomers(Request $request)
    {
        $dateRange = $request->query('date_range', 'all');
        $fromDate = $this->getFromDate($dateRange);
        
        $customers = Customer::with(['orders' => function($query) use ($fromDate) {
            $query->where('created_at', '>=', $fromDate);
        }])
        ->where('created_at', '>=', $fromDate)
        ->get();
        
        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers_report.csv"',
        ];
        
        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Customer ID', 'Name', 'Email', 'Orders', 'Total Spent', 'Joined Date']);
            
            // Add data rows
            foreach ($customers as $customer) {
                $totalSpent = $customer->orders->sum('total_price');
                
                fputcsv($file, [
                    $customer->id,
                    $customer->first_name . ' ' . $customer->last_name,
                    $customer->email,
                    $customer->orders->count(),
                    $totalSpent,
                    $customer->created_at->format('Y-m-d')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get the starting date based on date range
     */
    private function getFromDate($dateRange = 'all')
    {
        switch ($dateRange) {
            case '7d':
                return Carbon::now()->subDays(7);
            case '30d':
                return Carbon::now()->subDays(30);
            case '60d':
                return Carbon::now()->subDays(60);
            case '90d':
                return Carbon::now()->subDays(90);
            case '12m':
                return Carbon::now()->subMonths(12);
            case 'all':
            default:
                return Carbon::createFromTimestamp(0);
        }
    }

    /**
     * Get the previous period starting date
     */
    private function getPreviousPeriodDate($dateRange)
    {
        $currentFromDate = $this->getFromDate($dateRange);
        $now = Carbon::now();
        
        // Calculate the duration of the current period
        $periodDuration = $now->diffInSeconds($currentFromDate);
        
        // Calculate the start of the previous period
        return Carbon::createFromTimestamp($currentFromDate->timestamp - $periodDuration);
    }

    /**
     * Calculate percentage change
     */
    private function calculatePercentChange($current, $previous)
    {
        if ($previous == 0) {
            return 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Get order metrics
     */
    private function getOrderMetrics($fromDate, $prevFromDate)
    {
        // Current period metrics
        $metrics = Order::where('created_at', '>=', $fromDate)
            ->selectRaw('
                AVG(total_price) as avg_order_value, 
                COUNT(*) as total_orders
            ')
            ->first();
            
        // Previous period metrics for comparison
        $prevMetrics = Order::where('created_at', '>=', $prevFromDate)
            ->where('created_at', '<', $fromDate)
            ->selectRaw('
                AVG(total_price) as avg_order_value, 
                COUNT(*) as total_orders
            ')
            ->first();
            
        // Calculate conversion rate (requires visit data)
        $visits = DB::table('website_visits')
            ->where('created_at', '>=', $fromDate)
            ->count();
            
        $prevVisits = DB::table('website_visits')
            ->where('created_at', '>=', $prevFromDate)
            ->where('created_at', '<', $fromDate)
            ->count();
            
        $conversionRate = $visits > 0 ? ($metrics->total_orders / $visits) * 100 : 0;
        $prevConversionRate = $prevVisits > 0 ? ($prevMetrics->total_orders / $prevVisits) * 100 : 0;
        
        return [
            'avgOrderValue' => round($metrics->avg_order_value, 2),
            'avgOrderValueChange' => $this->calculatePercentChange(
                $metrics->avg_order_value, 
                $prevMetrics->avg_order_value
            ),
            'conversionRate' => round($conversionRate, 1),
            'conversionRateChange' => $this->calculatePercentChange(
                $conversionRate, 
                $prevConversionRate
            ),
            'totalOrders' => (int)$metrics->total_orders,
            'totalOrdersChange' => $this->calculatePercentChange(
                $metrics->total_orders, 
                $prevMetrics->total_orders
            )
        ];
    }

    /**
     * Get order segments
     */
    private function getOrderSegments($fromDate)
    {
        $segments = DB::table('orders')
            ->select(DB::raw('
                CASE 
                    WHEN total_price < 50 THEN "small" 
                    WHEN total_price BETWEEN 50 AND 150 THEN "medium" 
                    ELSE "large"
                END as type,
                COUNT(*) as count,
                AVG(total_price) as avg_value
            '))
            ->where('created_at', '>=', $fromDate)
            ->groupBy('type')
            ->get();
            
        $totalOrders = $segments->sum('count');
        
        return $segments->map(function($segment) use ($totalOrders) {
            return [
                'name' => $segment->type === 'small' ? 'Small Orders' : 
                          ($segment->type === 'medium' ? 'Medium Orders' : 'Large Orders'),
                'description' => $segment->type === 'small' ? 'Under $50' : 
                                ($segment->type === 'medium' ? '$50-$150' : 'Over $150'),
                'count' => (int)$segment->count,
                'percentage' => round(($segment->count / $totalOrders) * 100),
                'avgValue' => round($segment->avg_value),
                'type' => $segment->type
            ];
        });
    }

    /**
     * Get top products
     */
    private function getTopProducts($fromDate)
    {
        $topProducts = DB::table('order_items as oi')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->join('orders as o', 'oi.order_id', '=', 'o.id')
            ->select(
                'p.name',
                DB::raw('COUNT(DISTINCT o.id) as orders'),
                DB::raw('ROUND(AVG(oi.price), 2) as avg_price'),
                DB::raw('SUM(oi.quantity * oi.price) as total_revenue')
            )
            ->where('o.created_at', '>=', $fromDate)
            ->groupBy('p.id', 'p.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();
            
        $totalOrders = $topProducts->sum('orders');
        
        return $topProducts->map(function($product) use ($totalOrders) {
            return [
                'name' => $product->name,
                'orders' => (int)$product->orders,
                'percentage' => round(($product->orders / $totalOrders) * 100),
                'avgPrice' => $product->avg_price,
                'totalRevenue' => $product->total_revenue
            ];
        });
    }

    /**
     * Get order chart data
     */
    private function getOrderChartData($fromDate, $groupBy)
    {
        $groupFormat = $this->getGroupFormat($groupBy);
        
        $chartData = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.user_id')
            ->select(
                DB::raw("DATE_FORMAT(o.created_at, '{$groupFormat}') as date"),
                DB::raw('COUNT(CASE WHEN c.created_at >= ? AND c.created_at < DATE_ADD(o.created_at, INTERVAL 1 DAY) THEN 1 END) as new_orders'),
                DB::raw('COUNT(CASE WHEN c.created_at < ? THEN 1 END) as repeat_orders')
            )
            ->where('o.created_at', '>=', $fromDate)
            ->setBindings([$fromDate, $fromDate], 'select')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return $chartData->map(function($item) {
            return [
                'date' => $item->date,
                'newOrders' => (int)$item->new_orders,
                'repeatOrders' => (int)$item->repeat_orders
            ];
        });
    }

    /**
     * Get customer metrics
     */
    private function getCustomerMetrics($fromDate, $prevFromDate)
    {
        // Lifetime value calculation
        $ltv = DB::table(DB::raw('(
                SELECT orders.customer_id, SUM(total_price) as customer_total
                FROM orders
                INNER JOIN customers ON orders.customer_id = customers.user_id
                WHERE customers.created_at >= ?
                GROUP BY orders.customer_id
            ) as customer_totals'))
            ->select(DB::raw('AVG(customer_total) as value'))
            ->setBindings([$fromDate])
            ->first();
            
        $prevLtv = DB::table(DB::raw('(
                SELECT orders.customer_id, SUM(total_price) as customer_total
                FROM orders
                INNER JOIN customers ON orders.customer_id = customers.user_id
                WHERE customers.created_at >= ? AND customers.created_at < ?
                GROUP BY orders.customer_id
            ) as customer_totals'))
            ->select(DB::raw('AVG(customer_total) as value'))
            ->setBindings([$prevFromDate, $fromDate])
            ->first();
            
        // Retention rate calculation
        $totalCustomers = DB::table('customers')
            ->where('created_at', '<', $fromDate)
            ->count();
            
        $returningCustomers = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.user_id')
            ->where('o.created_at', '>=', $fromDate)
            ->where('c.created_at', '<', $fromDate)
            ->distinct('o.customer_id')
            ->count('o.customer_id');
            
        $retentionRate = $totalCustomers > 0 ? ($returningCustomers / $totalCustomers) * 100 : 0;
        
        // Previous period retention rate
        $prevTotalCustomers = DB::table('customers')
            ->where('created_at', '<', $prevFromDate)
            ->count();
            
        $prevReturningCustomers = DB::table('orders as o')
            ->join('customers as c', 'o.customer_id', '=', 'c.user_id')
            ->where('o.created_at', '>=', $prevFromDate)
            ->where('o.created_at', '<', $fromDate)
            ->where('c.created_at', '<', $prevFromDate)
            ->distinct('o.customer_id')
            ->count('o.customer_id');
            
        $prevRetentionRate = $prevTotalCustomers > 0 ? ($prevReturningCustomers / $prevTotalCustomers) * 100 : 0;
        
        // New customers
        $newCustomers = DB::table('customers')
            ->where('created_at', '>=', $fromDate)
            ->count();
            
        $prevNewCustomers = DB::table('customers')
            ->where('created_at', '>=', $prevFromDate)
            ->where('created_at', '<', $fromDate)
            ->count();
        
        return [
            'lifetimeValue' => number_format($ltv ? $ltv->value : 0, 2),
            'lifetimeValueChange' => $this->calculatePercentChange(
                $ltv ? $ltv->value : 0,
                $prevLtv ? $prevLtv->value : 0
            ),
            'retentionRate' => round($retentionRate, 1),
            'retentionRateChange' => $this->calculatePercentChange($retentionRate, $prevRetentionRate),
            'newCustomers' => $newCustomers,
            'newCustomersChange' => $this->calculatePercentChange($newCustomers, $prevNewCustomers)
        ];
    }

    /**
     * Get formatting for SQL GROUP BY
     */
    private function getGroupFormat($groupBy)
    {
        switch ($groupBy) {
            case 'week':
                return '%Y-%u'; // Year-Week
            case 'month':
                return '%Y-%m'; // Year-Month
            case 'day':
            default:
                return '%Y-%m-%d'; // Year-Month-Day
        }
    }
    
 /**
 * Get customer segments based on order history
 */
private function getCustomerSegments($fromDate)
{
    // First, get order counts per customer
    $customerOrderCounts = DB::table('orders')
        ->join('customers', 'orders.customer_id', '=', 'customers.user_id')
        ->where('orders.created_at', '>=', $fromDate)
        ->select(
            'orders.customer_id',
            DB::raw('COUNT(*) as order_count'),
            DB::raw('AVG(orders.total_price) as avg_price')
        )
        ->groupBy('orders.customer_id')
        ->get();
    
    // Now categorize customers by their order counts
    $segmentData = [
        'new' => ['count' => 0, 'total_spend' => 0],
        'repeat' => ['count' => 0, 'total_spend' => 0],
        'loyal' => ['count' => 0, 'total_spend' => 0]
    ];
    
    foreach ($customerOrderCounts as $customer) {
        $type = 'new';
        if ($customer->order_count > 5) {
            $type = 'loyal';
        } elseif ($customer->order_count > 1) {
            $type = 'repeat';
        }
        
        $segmentData[$type]['count']++;
        $segmentData[$type]['total_spend'] += $customer->avg_price;
    }
    
    // Format segments for the response
    $segments = collect();
    $totalCustomers = array_sum(array_column($segmentData, 'count'));
    
    foreach ($segmentData as $type => $data) {
        $avgSpend = $data['count'] > 0 ? $data['total_spend'] / $data['count'] : 0;
        
        $segments->push([
            'name' => $type === 'new' ? 'New Customers' : 
                    ($type === 'repeat' ? 'Repeat Customers' : 'Loyal Customers'),
            'description' => $type === 'new' ? 'First purchase within period' : 
                            ($type === 'repeat' ? '2-5 purchases' : '6+ purchases'),
            'count' => $data['count'],
            'percentage' => $totalCustomers > 0 ? round(($data['count'] / $totalCustomers) * 100) : 0,
            'avgSpend' => round($avgSpend),
            'type' => $type
        ]);
    }
    
    return $segments;
}
/**
 * Get customer chart data
 */
private function getCustomerChartData($fromDate, $groupBy)
{
    $groupFormat = $this->getGroupFormat($groupBy);
    
    $chartData = DB::table('customers as c')
        ->leftJoin('orders as o', function($join) use ($fromDate) {
            $join->on('c.user_id', '=', 'o.customer_id')
                 ->where('o.created_at', '>=', $fromDate);
        })
        ->select(
            DB::raw("DATE_FORMAT(o.created_at, '{$groupFormat}') as date"),
            DB::raw('COUNT(DISTINCT CASE WHEN c.created_at >= "' . $fromDate->toDateTimeString() . '" THEN c.user_id END) as new_customers'),
            DB::raw('COUNT(DISTINCT CASE WHEN c.created_at < "' . $fromDate->toDateTimeString() . '" THEN c.user_id END) as returning_customers')
        )
        ->where('o.created_at', '>=', $fromDate)
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    return $chartData->map(function($item) {
        return [
            'date' => $item->date,
            'newCustomers' => (int)$item->new_customers,
            'returningCustomers' => (int)$item->returning_customers
        ];
    });
}
}