<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    protected $fillable = [
        'memoire_id',
        'user_id',
    ];

    public function memoire(): BelongsTo
    {
        return $this->belongsTo(Memoire::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Logique métier
}