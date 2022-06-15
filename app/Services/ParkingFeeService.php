<?php

namespace App\Services;

use App\Models\EntryTransaction;
use App\Models\ParkingFee;
use App\Services\HelperService;
use Carbon\Carbon;

class ParkingFeeService
{
    private static function parkingPrice()
    {
        return HelperService::addPrevValue(ParkingFee::get()
                ->pluck('price')->toArray());
    }

    private static function parkingTime()
    {
        return HelperService::addPrevValue(ParkingFee::get()
                ->pluck('time_period')->toArray());
    }

    public static function totalTimeParkingPerToday($entryTransactionId)
    {
        $entryTransaction = EntryTransaction::where('transaction_number',
            $entryTransactionId)->first();

        $vehicleEntryTime = $entryTransaction->getRawOriginal('created_at');

        return ceil(Carbon::parse($vehicleEntryTime)->floatDiffInRealHours(
            now()
        ));
    }

    public static function totalTimeParkingPerDay($entryTransactionId)
    {
        return self::totalTimeParkingPerToday($entryTransactionId) > 24
            ? ceil(self::totalTimeParkingPerToday($entryTransactionId) / 24)
            : 0;
    }

    public static function totalPriceParkingPerDay($entryTransactionId)
    {
        $parkingPrice = self::parkingPrice();

        return self::totalTimeParkingPerDay($entryTransactionId) *
        end($parkingPrice);
    }

    public static function totalPriceParkingPerToday($entryTransactionId)
    {
        $totalPriceParkingToday = 0;

        foreach (self::parkingTime() as $index => $time) {
            if ($time >= self::totalTimeParkingPerToday($entryTransactionId)) {
                $totalPriceParkingToday = self::parkingPrice()[$index];

                break;
            }
        }

        return $totalPriceParkingToday;
    }

    public static function totalTimeParking($entryTransactionId)
    {
        return self::totalTimeParkingPerDay($entryTransactionId)
            ? self::totalTimeParkingPerDay($entryTransactionId) . ' Hari'
            : self::totalTimeParkingPerToday($entryTransactionId) . ' Jam';
    }

    public static function totalTimeParkingHuman($entryTransactionId)
    {
        $entryTransaction = EntryTransaction::where('transaction_number',
            $entryTransactionId)->first();

        return Carbon::parse(
            now()
        )->diffForHumans(
            $entryTransaction->getRawOriginal('created_at')
        );
    }

    public static function totalPriceParking($entryTransactionId)
    {
        return self::totalTimeParkingPerDay($entryTransactionId)
            ? self::totalPriceParkingPerDay($entryTransactionId)
            : self::totalPriceParkingPerToday($entryTransactionId);
    }

    public static function totalPriceParkingString($entryTransactionId)
    {
        return HelperService::setRupiahFormat(
            self::totalPriceParking($entryTransactionId),
            true
        );
    }
}
