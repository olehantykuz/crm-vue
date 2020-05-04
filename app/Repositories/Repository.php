<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

class Repository
{
    /**
     * @param Builder $query
     * @param string $column
     * @param int|null $month
     * @param int|null $year
     * @return Builder
     */
    protected function addMonthYearScope(Builder $query, string $column, ?int $month, ?int $year)
    {
        if ($month) {
            $year = $year ?: Carbon::now()->year;
            $query->whereYear($column, $year)
                ->whereMonth($column, $month);
        } elseif ($year) {
            $query->whereYear($column, $year);
        }

        return $query;
    }
}
