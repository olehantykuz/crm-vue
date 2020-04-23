<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultOptions extends Model
{
    use SoftDeletes;

    protected $table = 'default_options';
    protected $fillable = [
        'monthlyBudget'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
