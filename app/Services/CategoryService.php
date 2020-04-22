<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Currency;
use App\Models\User;

class CategoryService
{
    /**
     * @param array $data
     * @param User $user
     * @return Category
     */
    public function create(array $data, User $user)
    {
        $currency = Currency::find($data['currencyId']);

        $category = new Category();
        $category->title = $data['title'];
        $category->default_limit = (int) ($data['limit'] * 100);
        $category->currency()->associate($currency);
        $category->user()->associate($user);
        $category->save();

        return $category;
    }
}
