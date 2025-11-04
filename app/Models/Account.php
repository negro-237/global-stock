<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{hasManyThrough, HasMany};

class Account extends Model
{
    protected $fillable = [
        'name',
        'phone',
    ];

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): hasManyThrough
    {
        return $this->hasManyThrough(Product::class, Category::class);
    }

    public function supplies()
    {
        return Supply::whereHas('product.category', function ($query) {
            $query->where('account_id', $this->id);
        });
    }
}
