<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;


class tmqaytarish extends Model
{
    use HasFactory;
    protected $table = 'tmqaytarish';

    public function filial(): BelongsTo
    {
        return $this->belongsTo(filial::class);
    }

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

    public function pastavshik(): BelongsTo
    {
        return $this->belongsTo(pastavshik::class);
    }
}
