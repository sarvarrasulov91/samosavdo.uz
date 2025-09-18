<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class chiqim_taminot extends Model
{
    use HasFactory;
    protected $table = 'chiqim_taminot';

    public function valyuta(): BelongsTo
    {
        return $this->belongsTo(valyuta::class);
    }

    public function pastavshik(): BelongsTo
    {
        return $this->belongsTo(pastavshik::class);
    }
}
