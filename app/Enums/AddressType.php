<?php

namespace App\Enums;

/**
 * Address Type Enum
 */
enum AddressType: string
{
    case Billing = 'billing';
    case Shipping = 'shipping';
}