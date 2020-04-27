<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    /**
     * @param User $user
     * @param Category $category
     * @param array $data
     * @return Transaction
     */
    public function create(User $user, Category $category, array $data)
    {
        $currency = (new CurrencyService())->getById($data['currencyId']);
        $transaction = new Transaction();

        $transaction->type = $data['type'];
        $transaction->amount = (int) ($data['amount'] * 100);
        $transaction->description = $data['description'];
        $transaction->user()->associate($user);
        $transaction->category()->associate($category);
        $transaction->currency()->associate($currency);
        $transaction->save();

        return $transaction;
    }
}
