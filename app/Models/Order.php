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

    protected $casts = [
        'customer_id' => 'integer',
        'account_id' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $appends = ['client', 'amount', 'products'];

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

    public function getProductsAttribute()
    {
        return $this->transactions->map(function($item) {
            return [
                'name' => $item->product->name,
                'pu' => $item->product->price,
                'quantity' => $item->quantity
            ];
        });
    }
}
