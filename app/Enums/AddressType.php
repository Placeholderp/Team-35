<?php


namespace App\Enums;


/**
 * Class AddressType
 */
enum AddressType: string
{
    case Shipping = 'shipping';
    case Billing = 'billing';
}
