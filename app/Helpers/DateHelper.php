<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format datetime chuẩn Việt Nam
     */
    public static function format($value, string $format = 'H:i d/m/Y', string $fallback = '-'): string
    {
        if (!$value) {
            return $fallback;
        }
        try {
            return Carbon::parse($value)->format($format);
        } catch (\Exception $e) {
            return $fallback;
        }
    }

    /**
     * Format chỉ ngày: 23/04/2026
     */
    public static function date($value, string $format = 'd/m/Y', string $fallback = '-'): string
    {
        return self::format($value, $format, $fallback);
    }

    /**
     * Format chỉ giờ: 14:30
     */
    public static function time($value, string $format = 'H:i', string $fallback = '-'): string
    {
        return self::format($value, $format, $fallback);
    }

    /**
     * Hiển thị relative time: "2h trước", "1 ngày trước"
     */
    public static function timeAgo($value, string $fallback = '-'): string
    {
        if (!$value) {
            return $fallback;
        }
        try {
            return Carbon::parse($value)->diffForHumans(['short' => true]);
        } catch (\Exception $e) {
            return $fallback;
        }
    }

    /**
     * Format cho Blade: an toàn với null
     */
    public static function blade($value, string $format = 'H:i d/m/Y'): string
    {
        return self::format($value, $format);
    }
}