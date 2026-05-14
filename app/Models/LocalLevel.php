<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class LocalLevel extends Model
{
    protected $table = 'areas';

    protected $fillable = ['name', 'zone_id'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'zone_id');
    }

    public function dealers(): HasMany
    {
        return $this->hasMany(Dealer::class, 'area_id');
    }
}
