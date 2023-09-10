<?php

namespace App\Enums;

final class DeliveryTypes
{
    public const CONVENTIONAL = 0;
    public const EXPRESS = 1;
    public const URGENT = 2;
    public const SCHEDULED = 3;

    public static function getKey(int $deliveryType): string
    {
        switch ($deliveryType) {
            case self::CONVENTIONAL:
                return 'conventional';
            case self::EXPRESS:
                return 'express';
            case self::URGENT:
                return 'urgent';
            case self::SCHEDULED:
                return 'scheduled';
            default:
                return '';
        }
    }
}
