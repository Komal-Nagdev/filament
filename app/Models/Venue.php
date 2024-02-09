<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venue extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
    ];

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
