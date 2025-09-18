<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class kirim_dollar extends Model
{
    use HasFactory;
    protected $table = 'kirim_dollar';

    public function valyuta(): BelongsTo
    {
        return $this->belongsTo(valyuta::class);
    }

}
