<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDefaultOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('monthlyBudget')->default(100000);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('set null');
        });

        $defaultCurrencyId = DB::table('currencies')
            ->where('code', 'EUR')
            ->first()
            ->id;

        $userIds = DB::table('users')
            ->select('id')
            ->get()
            ->pluck('id');

        foreach ($userIds as $id) {
            DB::table('default_options')
                ->insert([
                    'user_id' => $id,
                    'monthlyBudget' => 100000,
                    'currency_id' => $defaultCurrencyId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
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
        Schema::table('default_options', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['currency_id']);
        });
        Schema::dropIfExists('default_options');
    }
}
