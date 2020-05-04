<?php

namespace App\Services\Bill;

use App\Repositories\TransactionRepository;

class CorrelatedBillService extends BillService implements BillServiceInterface
{
    /** @var TransactionRepository  */
    protected $transactionRepository;

    /**
     * RecentBillService constructor.
     */
    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
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
        $data = $this->transactionRepository->getAmountByCurrencies($userId, $month, $year);
        $totalAmounts = [];

        foreach ($data as $row) {
            $type = $row->type;
            $totalAmounts[$type] = $totalAmounts[$type] ?? [];
            $transactionAmountByCurrencies = json_decode($row->currencies_amount);
            if ($transactionAmountByCurrencies) {
                foreach ($transactionAmountByCurrencies as $code => $value) {
                    $totalAmounts[$type][$code] = $totalAmounts[$type][$code] ?? 0;
                    $totalAmounts[$type][$code] += $format ? $this->formatFromCents($value) : $value;
                    $totalAmounts[$type][$code] = $format ? round($totalAmounts[$type][$code], 2) : $totalAmounts[$type][$code];
                }
            }
        }

        return $this->combineResultTotalAmount($totalAmounts, $month, $year);
    }
}
