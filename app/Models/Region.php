<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
    ];

    public function venue(): HasOne
    {
        return $this->hasOne(Venue::class);
    }

    public function conference(): HasOne
    {
        return $this->hasOne(Conference::class);
    }
}
