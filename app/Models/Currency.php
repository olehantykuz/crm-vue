<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = [
        'code',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function conversation()
    {
        return $this->hasOne(CurrencyConversation::class);
    }

    /**
     * @return HasMany
     */
    public function defaultUsersOptions()
    {
        return $this->hasMany(DefaultOptions::class);
    }

    /**
     * @return HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
