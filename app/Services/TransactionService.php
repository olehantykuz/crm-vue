<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use stdClass;

class TransactionService
{
    protected $transactionRepository;

    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
    }

    /**
     * @param User $user
     * @param int|null $month
     * @param int|null $year
     * @return \Illuminate\Support\Collection
     */
    public function getUserTransactionsByDate(User $user, ?int $month = null, ?int $year = null)
    {
        return $this->transactionRepository
            ->getListByUser($user, $month, $year);
    }

    /**
     * @param User $user
     * @param int $transactionId
     * @return Collection|HasMany|HasMany[]|null
     *
     * @throws ModelNotFoundException
     */
    public function getUserTransactionById(User $user, int $transactionId)
    {
        return $user->transactions()
            ->with('category')
            ->findOrFail($transactionId);
    }

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
        $transaction->amount = (int)($data['amount'] * 100);
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
     * @return bool|int
     */
    public function saveDenormalizedTransaction($transaction)
    {
        $id = $transaction->id;
        $amountData = $this->calculateTransactionAmountForAllCurrencies($transaction);

        if ($this->transactionRepository->getDenormalizedRecordByTransactionId($id)) {
            return $this->transactionRepository->updateDenormalizedRecord($id, $amountData);
        } else {
            return $this->transactionRepository->createDenormalizedRecord($id, $amountData);
        }
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
                    : (int)($transaction->amount * $currencyService->calculateConversationRate($conversation, $conversations->get($transactionCurrencyId)));

                $data[$currencies->get($currencyId)] = $amount;
            }
        }

        return $data;
    }
}
