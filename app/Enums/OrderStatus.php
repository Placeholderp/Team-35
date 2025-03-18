<?php

namespace App\Enums;

/**
 * Class OrderStatus
 */
enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Paid = 'paid';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    /**
     * Get all available order statuses
     * 
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::Pending, 
            self::Processing, 
            self::Paid, 
            self::Shipped, 
            self::Delivered, 
            self::Completed, 
            self::Cancelled, 
            self::Refunded
        ];
    }
    
    /**
     * Get statuses that represent active orders
     * 
     * @return array
     */
    public static function getActiveStatuses()
    {
        return [
            self::Pending,
            self::Processing,
            self::Paid,
            self::Shipped
        ];
    }
    
    /**
     * Get statuses that represent completed orders
     * 
     * @return array
     */
    public static function getCompletedStatuses()
    {
        return [
            self::Delivered,
            self::Completed
        ];
    }
    
    /**
     * Get statuses that represent terminated orders
     * 
     * @return array
     */
    public static function getTerminatedStatuses()
    {
        return [
            self::Cancelled,
            self::Refunded
        ];
    }
}