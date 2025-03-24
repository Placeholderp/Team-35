<?php


namespace App\Enums;


/**
 * Class CustomerStatus
 */
enum CustomerStatus: int
{
    case Active = 1;
    case Disabled = 0;
}
