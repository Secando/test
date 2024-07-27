<?php

namespace App\Enums;

enum PaymentStatus : int
{
    case WAITING_PAYMENT = 1;
    case PAYMENT = 2;

}
