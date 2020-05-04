<?php

namespace App\Console\Commands;

use App\Services\TransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransactionDenormalize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:denormalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate transactions amount for all currencies';

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
        $transactionService = new TransactionService();
        $transactions = DB::table('transactions')
            ->get();
        foreach ($transactions as $transaction) {
            $transactionService->saveDenormalizedTransaction($transaction);
        }
    }
}
