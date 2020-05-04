<?php

namespace App\Services\Bill;

class BillServiceFactory
{
    /**
     * @param bool $correlatedMode
     * @return BillServiceInterface
     */
    public static function getInstance($correlatedMode = false)
    {
        return $correlatedMode ? new CorrelatedBillService() : new RecentBillService();
    }
}
