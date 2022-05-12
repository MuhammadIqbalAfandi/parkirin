<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TopUpService extends CurrencyFormatService
{
    public function topUpRank(EloquentCollection $collections, int $take = 5)
    {
        return $collections
            ->transform(fn($collects) => [[
                'name' => $collects->first()->member->name,
                'phone' => $collects->first()->member->phone,
                'amount' => $collects->sum(fn($collect) => $collect->getRawOriginal('amount')),
            ]])
            ->sortByDesc('amount')
            ->take($take)
            ->flatten(1);
    }
}
