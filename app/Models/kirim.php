<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;


class kirim extends Model
{
    use HasFactory;
    protected $table = 'kirim';

    public function filial(): BelongsTo
    {
        return $this->belongsTo(filial::class);
    }

    public function kirimtur(): BelongsTo
    {
        return $this->belongsTo(kirimtur::class);
    }

    public function valyuta(): BelongsTo
    {
        return $this->belongsTo(valyuta::class);
    }

}
