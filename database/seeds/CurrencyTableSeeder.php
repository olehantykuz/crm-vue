<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            ['code' => 'EUR', 'created_at' => Carbon::now()],
            ['code' => 'USD', 'created_at' => Carbon::now()],
            ['code' => 'UAH', 'created_at' => Carbon::now()],
            ['code' => 'RUB', 'created_at' => Carbon::now()],
            ['code' => 'PLN', 'created_at' => Carbon::now()],
        ];

        DB::table('currencies')
            ->insertOrIgnore($currencies);
    }
}
