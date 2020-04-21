<?php

namespace App\Console\Commands;

use App\Services\CurrencyService;
use Illuminate\Console\Command;

class CurrencyRefreshConversationRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating exchange rates for currencies from the database regarding default currency from configs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.*
     */
    public function handle()
    {
        $currencyService = new CurrencyService();
        $rates = $currencyService->getConversationRates();

        foreach ($rates as $code => $rate) {
            $currency = $currencyService->getCurrencyByCode($code);
            if ($currency) {
                $currencyService->updateConversation($currency, $rate);
            }
        }
    }
}
