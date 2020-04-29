<?php

namespace App\Services\Bill;

interface BillServiceInterface
{
    /**
     * @param int $userId
     * @param int|null $month
     * @param bool|null $format
     * @return array
     */
    public function getTotalAmountInCurrencies(int $userId, ?int $month = null, ?bool $format = false): array;

}
