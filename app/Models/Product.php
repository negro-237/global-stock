<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description'
    ];

    protected $appends = ['category_name', 'quantity'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function customers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }

    public function supplies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Supply::class);
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category ? $this->category->name : '';
    }

    public function getQuantityAttribute(): int
    {
        return $this->supplies()->sum('quantity');
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => strtolower($value)
        );
    }
}
