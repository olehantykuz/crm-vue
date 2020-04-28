<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDnmzTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dnmz_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->text('currencies_amount');
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->cascadeOnDelete();
        });

        $transactionService = new \App\Services\TransactionService();
        $transactions = DB::table('transactions')
            ->get();
        foreach ($transactions as $transaction) {
            $denormalizedData = $transactionService->calculateTransactionAmountForAllCurrencies($transaction);
            DB::table('dnmz_transactions')
                ->insert([
                    'transaction_id' => $transaction->id,
                    'currencies_amount' => json_encode($denormalizedData),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dnmz_transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
        });
        Schema::dropIfExists('dnmz_transactions');
    }
}
