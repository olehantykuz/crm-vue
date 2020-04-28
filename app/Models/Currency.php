<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    protected $table = 'currencies';
    protected $fillable = [
        'code',
    ];

    /**
     * @return HasOne
     */
    public function conversation()
    {
        return $this->hasOne(CurrencyConversation::class);
    }

    /**
     * @return HasMany
     */
    public function conversations()
    {
        return $this->hasMany(CurrencyConversation::class);
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function latestConversation()
    {
        return $this->conversations()
            ->latest()
            ->first();
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

    /**
     * @return HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
