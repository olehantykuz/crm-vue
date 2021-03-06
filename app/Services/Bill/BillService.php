<?php

namespace App\Services\Bill;

abstract class BillService implements BillServiceInterface
{
    /**
     * @param int $userId
     * @param int|null $month
     * @param int|null $year
     * @param bool|null $format
     * @return array
     */
    abstract public function getTotalAmountInCurrencies(int $userId, ?int $month = null, ?int $year = null, ?bool $format = false): array;

    /**
     * @param int $value
     * @return float
     */
    protected function formatFromCents(int $value)
    {
        return round($value / 100, 2);
    }

    /**
     * @param array $data
     * @param int|null $month
     * @param int|null $year
     * @return array
     */
    protected function combineResultTotalAmount(array $data, ?int $month = null, ?int $year = null): array
    {
        return [
            'amounts' => $data,
            'date' => [
                'month' => $month,
                'year' => $year,
            ],
        ];
    }
}
