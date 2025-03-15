<?php


namespace App\Enums;


/**
 * Class PaymentStatus
 */
enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
}
