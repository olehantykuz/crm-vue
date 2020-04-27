<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    /**
     * @param int $userId
     * @return Collection
     */
    public function getTotals(int $userId)
    {
        return DB::table('transactions')
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select(DB::raw('SUM(amount) as total, type, code'))
            ->where('user_id', $userId)
            ->groupBy('code', 'type')
            ->get();
    }

    /**
     * @param int $userId
     * @return Collection
     */
    public function getTotalsByCategories(int $userId)
    {
        return DB::table('transactions')
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select(DB::raw('SUM(amount) as total, type, category_id, code'))
            ->where('transactions.user_id', $userId)
            ->groupBy(['code', 'category_id', 'type'])
            ->get();
    }
}
