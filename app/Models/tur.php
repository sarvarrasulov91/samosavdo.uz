<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class tur extends Model
{
    use HasFactory;
    protected $table='tur';

    public function transport(): BelongsTo
    {
        return $this->belongsTo(transport::class);
    }

    public function tmodel(): HasOne
    {
        return $this->hasOne(tmodel::class);
    }

    public function ktovar1(): HasOne
    {
        return $this->hasOne(kirimTovar::class);
    }

    public function savdo1(): HasOne
    {
        return $this->hasOne(savdo::class);
    }

    public function natsenka(): BelongsTo
    {
        return $this->belongsTo(natsenka::class);
    }

    public function talmashish(): HasOne
    {
        return $this->hasOne(talmashish::class);
    }

    public function savdobonus1(): HasOne
    {
        return $this->hasOne(bonusSavdo::class);
    }

    public function tqaytarish(): HasOne
    {
        return $this->hasOne(tqaytarish::class);
    }

    public function tmqaytarish(): HasOne
    {
        return $this->hasOne(tmqaytarish::class);
    }

}
