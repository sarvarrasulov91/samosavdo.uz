<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class mfy extends Model
{
    use HasFactory;
    protected $table = 'mfy';

    public function tuman(): BelongsTo
    {
        return $this->belongsTo(tuman::class);
    }


    public function mijozlar(): HasOne
    {
        return $this->hasOne(mijozlar::class);
    }

}
