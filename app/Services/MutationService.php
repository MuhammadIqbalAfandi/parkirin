<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;

class MutationService
{
    public static function totalIncome(EloquentCollection $collections)
    {
        return $collections->sum(function ($collect) {
            if ($collect->getRawOriginal('type') == 1) {
                return $collect->getRawOriginal('amount');
            }
        });
    }

    public static function totalExpense(EloquentCollection $collections)
    {
        return $collections->sum(function ($collect) {
            if ($collect->getRawOriginal('type') == 2) {
                return $collect->getRawOriginal('amount');
            }
        });
    }

    public static function totalIncomeAsString(EloquentCollection $collections)
    {
        if ($collections->count()) {
            return HelperService::setRupiahFormat(
                self::totalIncome($collections),
                true
            );
        } else {
            return HelperService::setRupiahFormat(0, true);
        }
    }

    public static function totalExpenseAsString(EloquentCollection $collections)
    {
        if ($collections->count()) {
            return HelperService::setRupiahFormat(
                self::totalExpense($collections),
                true
            );
        } else {
            return HelperService::setRupiahFormat(0, true);
        }
    }

    public static function totalAmountAsString(EloquentCollection $collections)
    {
        if ($collections->count()) {
            $amount = self::totalIncome($collections) - self::totalExpense($collections);
        } else {
            $amount = 0;
        }

        return HelperService::setRupiahFormat($amount, true);
    }

    public static function totalPerMonth(EloquentCollection $collections)
    {
        return $collections->transform(fn($collection) =>
            $collection->sum(fn($collect) =>
                $collect->getRawOriginal('amount')
            )
        );
    }

    public static function statistic(SupportCollection $collections)
    {
        $collections = $collections;
        $collections->transform(fn($collections) =>
            self::totalPerMonth($collections));
        return $collections;
    }
}
