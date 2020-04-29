<?php

namespace App\Services\Bill;

use App\Repositories\CurrencyRepository;
use App\Repositories\TransactionRepository;
use App\Services\CurrencyService;

class RecentBillService extends BillService implements BillServiceInterface
{
    /** @var TransactionRepository  */
    protected $transactionRepository;
    /** @var CurrencyRepository  */
    protected $currencyRepository;
    /** @var CurrencyService  */
    protected $currencyService;

    /**
     * RecentBillService constructor.
     */
    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
        $this->currencyRepository = new CurrencyRepository();
        $this->currencyService = new CurrencyService();
    }

    /**
     * @param int $userId
     * @param int|null $month
     * @param int|null $year
     * @param bool|null $format
     * @return array
     */
    public function getTotalAmountInCurrencies(int $userId, ?int $month = null, ?int $year = null, ?bool $format = false): array
    {
        $data = $this->transactionRepository->getTotals($userId, $month, $year);
        $conversations = $this->currencyRepository->getLastConversations($month, $year);

        $totalAmounts = [];
        foreach ($data as $row) {
            $type = $row->type;
            $totalAmounts[$type] = $totalAmounts[$type] ?? [];
            foreach ($conversations as $conversation) {
                $code = $conversation->code;
                $totalAmounts[$type][$code] = $totalAmounts[$type][$code] ?? 0;
                $amount = $row->code == $code
                    ? $row->total
                    : (int) ($row->total * $this->currencyService->calculateConversationRate(
                        $conversation, $conversations[$row->code]
                    ));
                $totalAmounts[$type][$code] += $format ? $this->formatFromCents($amount) : (int) $amount;
                $totalAmounts[$type][$code] = $format ? round($totalAmounts[$type][$code], 2) : $totalAmounts[$type][$code];
            }
        }

        return $this->combineResultTotalAmount($totalAmounts, $month, $year);
    }
}
