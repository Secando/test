<?php

namespace App\Enums;

enum EmailQueueStatus : int
{
    case PENDING = 1;
    case SUCCESS = 2;
    case FAILED = 3;
}
