<?php

use App\Services\TransactionService;
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

        $transactionService = new TransactionService();
        $transactions = DB::table('transactions')
            ->get();
        foreach ($transactions as $transaction) {
            $transactionService->saveDenormalizedTransaction($transaction);
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
