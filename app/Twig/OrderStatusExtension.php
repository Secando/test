<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class OrderStatusExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('order_status', [$this, 'getOrderStatus']),
        ];
    }

    public function getOrderStatus(int $status): string
    {
        switch ($status) {
            case 1:
                return 'В очереди';
            case 2:
                return 'Ожидает Оплаты';
            case 3:
                return 'В процессе';
            case 4:
                return 'В доставке';
            case 5:
                return 'Доставлено';
            default:
                return 'Unknown';
        }
    }
}