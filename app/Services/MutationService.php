<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;

class MutationService extends CurrencyFormatService
{
    public function totalIncome(EloquentCollection $collections)
    {
        foreach ($collections->chunk(100) as $chunk) {
            return $chunk->sum(function ($collect) {
                if ($collect->getRawOriginal('type') == 1) {
                    return $collect->getRawOriginal('amount');
                }
            });
        }
    }

    public function totalExpense(EloquentCollection $collections)
    {
        foreach ($collections->chunk(100) as $chunk) {
            return $chunk->sum(function ($collect) {
                if ($collect->getRawOriginal('type') == 2) {
                    return $collect->getRawOriginal('amount');
                }
            });
        }
    }

    public function totalIncomeAsString(EloquentCollection $collections)
    {
        if ($collections->count()) {
            return $this->setRupiahFormat($this->totalIncome($collections), true);
        } else {
            return $this->setRupiahFormat(0, true);
        }
    }

    public function totalExpenseAsString(EloquentCollection $collections)
    {
        if ($collections->count()) {
            return $this->setRupiahFormat($this->totalExpense($collections), true);
        } else {
            return $this->setRupiahFormat(0, true);
        }
    }

    public function totalAmountAsString(EloquentCollection $collections)
    {
        if ($collections->count()) {
            $amount = $this->totalIncome($collections) - $this->totalExpense($collections);
        } else {
            $amount = 0;
        }

        return $this->setRupiahFormat($amount, true);
    }

    public function totalPerMonth(EloquentCollection $collections)
    {
        return $collections->transform(fn($collection) => $collection->sum(fn($collect) => $collect->getRawOriginal('amount')));
    }

    public function statisticData(SupportCollection $collections, int $take = -1)
    {
        $collections = $collections->take($take);
        $collections->transform(fn($collections) => $this->totalPerMonth($collections));
        return $collections;
    }
}
