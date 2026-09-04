<?php

if (!function_exists('format_currency')) {
    function format_currency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_date')) {
    function format_date($date, $format = 'd/m/Y')
    {
        if (!$date) return '-';
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime($date)
    {
        if (!$date) return '-';
        return \Carbon\Carbon::parse($date)->format('d/m/Y H:i');
    }
}

if (!function_exists('get_status_badge_class')) {
    function get_status_badge_class($status)
    {
        return \App\Enums\ProjectStatusEnum::tryFrom($status)?->badgeColor() ?? 'gray';
    }
}

if (!function_exists('get_status_label')) {
    function get_status_label($status)
    {
        return \App\Enums\ProjectStatusEnum::tryFrom($status)?->label() ?? $status;
    }
}

if (!function_exists('get_logo')) {
    function get_logo()
    {
        $setting = \App\Models\Setting::where('key', 'logo_path')->first();
        if ($setting && $setting->value) {
            return Storage::url($setting->value);
        }
        return null;
    }

    if (!function_exists('greeting')) {
    function greeting()
    {
        $hour = now()->format('H');
        if ($hour >= 5 && $hour < 11) {
            return 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            return 'Selamat Sore';
        } elseif ($hour >= 18 && $hour < 24) {
            return 'Selamat Malam';
        } else {
            return 'Selamat Dini Hari'; // 00:00 - 04:59
        }
    }
}
}