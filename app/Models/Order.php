<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'account_id'
    ];

    protected $appends = ['client', 'amount'];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function getAmountAttribute()
    {
        $amount = $this->transactions->sum(function($trx) {
            return $trx->quantity * $trx->product->price;
        });

        return number_format($amount, 0, '', ' ');
    }

    protected function getClientAttribute(): string
    {
        return $this->customer->name;
    }
}
