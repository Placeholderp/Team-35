<?php

namespace App\Traits;

use Carbon\Carbon;

trait ReportTrait
{
    /**
     * Get the starting date based on date range parameter
     */
    private function getFromDate($dateRange = 'all')
    {
        // Support both old format (d parameter) and new format (date_range parameter)
        if (is_null($dateRange)) {
            $request = \request();
            $paramDate = $request->get('d');
            
            // Define an array mapping period codes to corresponding dates
            $array = [
                '1d' => Carbon::now()->subDays(1),    
                '1k' => Carbon::now()->subDays(7),    
                '2k' => Carbon::now()->subDays(14),   
                '1m' => Carbon::now()->subDays(30),  
                '3m' => Carbon::now()->subDays(60),   
                '6m' => Carbon::now()->subDays(180),  
            ];
            
            return $array[$paramDate] ?? Carbon::createFromTimestamp(0);
        }
        
        // New format using date_range parameter
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
}