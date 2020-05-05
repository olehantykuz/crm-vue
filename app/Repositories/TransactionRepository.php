<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionRepository extends Repository
{
    /**
     * @param User $user
     * @param int|null $month
     * @param int|null $year
     * @return Collection
     */
    public function getListByUser(User $user, ?int $month = null, ?int $year = null)
    {
        $query = $user->transactions();
        $query = $this->addIntervalScope($query, $month, $year);

        return $query->get();
    }

    /**
     * @param int $userId
     * @param int|null $month
     * @param int|null $year
     * @return Collection
     */
    public function getTotals(int $userId, ?int $month = null, ?int $year = null)
    {
        $query = DB::table('transactions')
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select(DB::raw('SUM(amount) as total, type, code'))
            ->where('user_id', $userId);
        $query = $this->addIntervalScope($query, $month, $year);

        return $query->groupBy('code', 'type')
            ->get();
    }

    /**
     * @param int $userId
     * @param int|null $month
     * @param int|null $year
     * @return Collection
     */
    public function getAmountByCurrencies(int $userId, ?int $month = null, ?int $year = null)
    {
        $query = DB::table('transactions')
            ->join('dnmz_transactions', 'transactions.id', '=', 'dnmz_transactions.transaction_id')
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select('type', 'code', 'amount', 'currencies_amount')
            ->where('user_id', $userId);
        $query = $this->addIntervalScope($query, $month, $year);

        return $query->get();
    }

    /**
     * @param int $userId
     * @param int|null $month
     * @param int|null $year
     * @return Collection
     */
    public function getTotalsByCategories(int $userId, ?int $month = null, ?int $year = null)
    {
        $query = DB::table('transactions')
            ->join('categories', 'categories.id', '=', 'transactions.category_id')
            ->join('currencies', 'currencies.id', '=', 'transactions.currency_id')
            ->select(DB::raw('SUM(amount) as total, type, category_id, code'))
            ->where('transactions.user_id', $userId);
        $query = $this->addIntervalScope($query, $month, $year);

        return $query->groupBy(['code', 'category_id', 'type'])
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
     * @return \Illuminate\Database\Eloquent\Model|Builder|object|null
     */
    public function getDenormalizedRecordByTransactionId(int $id)
    {
        return DB::table('dnmz_transactions')
            ->where('transaction_id', $id)
            ->first();
    }

    /**
     * @param Builder|Relation $query
     * @param int|null $month
     * @param int|null $year
     * @return Builder
     */
    protected function addIntervalScope($query, ?int $month, ?int $year)
    {
        return $this->addMonthYearScope($query, 'transactions.created_at', $month, $year);
    }
}
