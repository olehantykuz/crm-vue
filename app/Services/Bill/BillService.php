<?php

namespace App\Services\Bill;

abstract class BillService implements BillServiceInterface
{
    /**
     * @param int $userId
     * @param int|null $month
     * @param bool|null $format
     * @return array
     */
    abstract public function getTotalAmountInCurrencies(int $userId, ?int $month = null, ?bool $format = false): array;

    /**
     * @param int $value
     * @return float
     */
    protected function formatFromCents(int $value)
    {
        return round($value / 100, 2);
    }
}
