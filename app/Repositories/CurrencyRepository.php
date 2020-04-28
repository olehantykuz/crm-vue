<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CurrencyRepository
{
    /**
     * @param string $date
     * @return string|null
     */
    public function getNearestConversationDateByDate(string $date)
    {
        $nearestConversation  = DB::table('currency_conversations')
            ->whereDate('created_at', '>=', $this->toDateString($date))
            ->select('created_at')
            ->first();

        return $nearestConversation ? $nearestConversation->created_at : null;
    }

    /**
     * @return string|null
     */
    public function getLatestConversationDate()
    {
        $latestConversation = DB::table('currency_conversations')
            ->select('created_at')
            ->orderByDesc('created_at')
            ->first();

        return $latestConversation ? $latestConversation->created_at : null;
    }

    /**
     * @param string $date
     * @return Collection
     */
    public function getConversationsByDate(string $date)
    {
        return DB::table('currency_conversations')
            ->whereDate('created_at', $this->toDateString($date))
            ->get();
    }

    /**
     * @param string $date
     * @return string
     */
    private function toDateString(string $date)
    {
        return (Carbon::parse($date))->toDateString();
    }
}
