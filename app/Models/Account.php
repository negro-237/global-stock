<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name',
        'phone',
    ];

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    public function scopeUnsynced($query)
    {
        return $query->where('synced', false);
    }

    public function markAsSynced()
    {
        $this->update([
            'synced' => true,
            'last_synced_at' => now()
        ]);
    }
}
