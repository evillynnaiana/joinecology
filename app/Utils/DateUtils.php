<?php

namespace App\Utils;

class DateUtils
{
    public static function format(string $date, string $format = 'd/m/Y H:i:s'): string
    {
        $dateTime = new \DateTime($date);
        return $dateTime->format($format);
    }

    public static function convertToUserTimezone(string $date): string
    {
        $timezone = 'America/Sao_Paulo';
        $dateTime = new \DateTime($date, new \DateTimeZone('UTC'));
        $dateTime->setTimezone(new \DateTimeZone($timezone));
        return $dateTime->format('Y-m-d H:i:s');
    }
}
