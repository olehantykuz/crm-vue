<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DenormalizedTransaction extends Model
{
    protected $table = 'dnmz_transactions';
    protected $fillable = [
        'currencies_amount',
    ];
    protected $casts = [
        'currencies_amount' => 'array'
    ];

    /**
     * @return array|mixed
     */
    public function getFormattedCurrenciesAmountAttribute()
    {
        if ($this->currencies_amount) {
            $result = [];
            foreach ($this->currencies_amount as $code => $value) {
                $result[$code] = $value / 100;
            }

            return $result;
        }

        return $this->currencies_amount;
    }

    /**
     * @return BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

}
