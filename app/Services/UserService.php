<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\DefaultOptions;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $currency = Currency::find($data['currencyId']);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $defaultOptions = new DefaultOptions();
        $defaultOptions->monthly_budget = $data['monthlyBudget'];
        $defaultOptions->currency()->associate($currency);
        $defaultOptions->user()->associate($user);
        $defaultOptions->save();

        return $user;
    }
}
