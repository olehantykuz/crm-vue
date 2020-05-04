<?php

namespace App\Services\Bill;

interface BillServiceInterface
{
    /**
     * @param int $userId
     * @param int|null $month
     * @param int|null $year
     * @param bool|null $format
     * @return array
     */
    public function getTotalAmountInCurrencies(int $userId, ?int $month = null, ?int $year = null, ?bool $format = false): array;

}
