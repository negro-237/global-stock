<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = [
        'quantity',
        'product_id'
    ];

    protected $casts = [
        'quantity' => 'float',
        'created_at' => 'date:d-m-Y H:i:s',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
