<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TopUpService
{
    public static function topStatistic(EloquentCollection $collections, int $take = 5)
    {
        return $collections
            ->transform(fn($collects) => [[
                'label1' => $collects->first()->member->name,
                'label2' => $collects->first()->member->phone,
                'data' => $collects->sum(fn($collect) =>
                    $collect->getRawOriginal('amount')
                )
            ]])
            ->sortByDesc('amount')
            ->take($take)
            ->flatten(1);
    }
}
