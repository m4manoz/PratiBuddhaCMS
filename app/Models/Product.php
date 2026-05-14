<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'category',
        'specifications',
        'image_path'
    ];

    protected $casts = [
        'specifications' => 'array',
        'price' => 'decimal:2',
    ];

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }
}
