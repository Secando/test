<?php

namespace App\Enums;

enum OrderStatus : int
{
    case PENDING = 1;
    case WAITING_PAYMENT = 2;
    case PROCESSING = 3;
    case DELIVERED = 4;
    case RECEIVED = 5;

}
