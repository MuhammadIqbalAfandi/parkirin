<?php

namespace App\Services;

class CurrencyFormatService
{
    public function setRupiahFormat(int $number, bool $sign = false)
    {
        if ($sign) {
            if ($number < 0) {
                return '-Rp' . number_format(abs($number), 0, ',', '.');
            } else {
                return 'Rp' . number_format($number, 0, ',', '.');
            }
        } else {
            if ($number < 0) {
                return '-' . number_format(abs($number), 0, ',', '.');
            } else {
                return number_format($number, 0, ',', '.');
            }
        }
    }
}
