<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class brend extends Model
{
    use HasFactory;

    protected $table='brend';

    public function tmodel(): HasOne
    {
        return $this->hasOne(tmodel::class);
    }

    public function ktovar1(): HasOne
    {
        return $this->hasOne(ktovar1::class);
    }

    public function savdo1(): HasOne
    {
        return $this->hasOne(savdo1::class);
    }

    public function natsenka(): BelongsTo
    {
        return $this->belongsTo(natsenka::class);
    }

    public function talmashish(): BelongsTo
    {
        return $this->belongsTo(talmashish::class);
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
