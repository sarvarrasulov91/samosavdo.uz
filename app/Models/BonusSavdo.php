<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class bonusSavdo extends Model
{
    use HasFactory;
    protected $table = 'bonus_savdo';

    public function tur(): BelongsTo
    {
        return $this->belongsTo(tur::class);
    }

    public function brend(): BelongsTo
    {
        return $this->belongsTo(brend::class);
    }

    public function tmodel(): BelongsTo
    {
        return $this->belongsTo(tmodel::class);
    }
}
