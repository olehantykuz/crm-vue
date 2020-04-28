<?php

namespace App\Repositories;

use Carbon\Carbon;
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

    /**
     * @param int $transactionId
     * @param array $data
     * @return bool
     */
    public function createDenormalizedRecord(int $transactionId, array $data)
    {
        return DB::table('dnmz_transactions')
            ->insert([
                'transaction_id' => $transactionId,
                'currencies_amount' => json_encode($data),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * @param int $transactionId
     * @param array $data
     * @return int
     */
    public function updateDenormalizedRecord(int $transactionId, array $data)
    {
        return DB::table('dnmz_transactions')
            ->where('transaction_id', $transactionId)
            ->update([
                'currencies_amount' => json_encode($data),
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getDenormalizedRecordByTransactionId(int $id)
    {
        return DB::table('dnmz_transactions')
            ->where('transaction_id', $id)
            ->first();
    }
}
