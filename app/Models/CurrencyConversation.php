<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyConversation extends Model
{
    protected $table = 'currencies';
    protected $fillable = [
        'rate',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
