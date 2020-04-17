<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currency_conversations';
    protected $fillable = [
        'name',
        'conversion_rate',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function conversation()
    {
        return $this->hasOne(CurrencyConversation::class);
    }
}
