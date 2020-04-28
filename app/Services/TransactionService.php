<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

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
        $amountByCurrencies = $this->calculateTransactionAmountForAllCurrencies($transaction);

        $transaction->save();
        $transaction->denormalized()->create([
            'currencies_amount' => $amountByCurrencies
        ]);

        return $transaction;
    }

    /**
     * @param Transaction|stdClass $transaction
     * @return array
     */
    public function calculateTransactionAmountForAllCurrencies($transaction)
    {
        $data = [];
        $currencyService = new CurrencyService();
        $currencies = $currencyService->getAll()
            ->pluck('code', 'id');
        $defaultCurrencyId = $currencies->search($currencyService->getDefaultCurrencyCode());

        $transactionCurrencyId = $transaction->currency_id;
        $date = (Carbon::parse($transaction->created_at))->toDateString();
        $conversations = $currencyService->getRelevantConversations($date);

        if ($conversations->isNotEmpty() && $defaultCurrencyId) {
            $conversations = $conversations->keyBy('currency_id');
            foreach ($conversations as $currencyId => $conversation) {
                $amount = $transactionCurrencyId == $currencyId
                    ? $transaction->amount
                    : (int) ($transaction->amount * $conversation->rate / $conversations->get($transactionCurrencyId)->rate);

                $data[$currencies->get($currencyId)] = $amount;
            }
        }

        return $data;
    }
}
