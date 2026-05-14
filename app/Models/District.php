<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'zones';

    protected $fillable = ['name', 'province_id'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function localLevels(): HasMany
    {
        return $this->hasMany(LocalLevel::class, 'zone_id');
    }

    public function areas(): HasMany
    {
        return $this->localLevels();
    }
}