<?php

namespace App\Services;

class BadgeService
{
    private const BADGE_MULTIPLIERS = [
        'شارات رياضية' => 300,
        'شارات دينيه' => 300,
        'شارات كشفيه' => 500,
        'شارات مهاريه' => 300,
        'شارات فنيه' => 300,
        'كشاف تاني' => 2500,
        'كشاف أول' => 5000,
        'شارة الطليعة' => 1000,
        'مخيم' => 5000,
    ];

    public static function calculatePoints(string $badgeName, int $quantity): int
    {
        return $quantity * (self::BADGE_MULTIPLIERS[$badgeName] ?? 0);
    }

    public static function getMultiplier(string $badgeName): int
    {
        return self::BADGE_MULTIPLIERS[$badgeName] ?? 0;
    }

    public static function getAllMultipliers(): array
    {
        return self::BADGE_MULTIPLIERS;
    }
} 