<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Currency;
use App\Models\User;

class CategoryService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function getAllByUser(User $user)
    {
        return $user->categories;
    }

    /**
     * @param array $data
     * @param User $user
     * @return Category
     */
    public function create(array $data, User $user)
    {
        $currency = (new CurrencyService())->getById($data['currencyId']);

        $category = new Category();
        $category->title = $data['title'];
        $category->default_limit = (int) ($data['limit'] * 100);
        $category->currency()->associate($currency);
        $category->user()->associate($user);
        $category->save();

        return $category;
    }

    /**
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function update(Category $category, array $data)
    {
        $currency = (new CurrencyService())->getById($data['currencyId']);

        $category->title = $data['title'];
        $category->default_limit = (int) ($data['limit'] * 100);
        $category->currency()->associate($currency);
        $category->save();

        return $category;
    }
}
