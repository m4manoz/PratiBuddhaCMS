<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = ['name', 'zone_id'];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function dealers(): HasMany
    {
        return $this->hasMany(Dealer::class);
    }
}
