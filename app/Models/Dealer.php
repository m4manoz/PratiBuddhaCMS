<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dealer extends Model
{
    protected $fillable = [
        'name',
        'contact_number',
        'email',
        'address',
        'latitude',
        'longitude',
        'ward',
        'street_tole',
        'area_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(LocalLevel::class, 'area_id');
    }

    public function localLevel(): BelongsTo
    {
        return $this->belongsTo(LocalLevel::class, 'area_id');
    }
}