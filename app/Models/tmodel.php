<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;



class tmodel extends Model
{
    use HasFactory;
    protected $table='tmodel';


    public function tur(): BelongsTo
    {
        return $this->belongsTo(tur::class);
    }

    public function brend(): BelongsTo
    {
        return $this->belongsTo(brend::class);
    }

    public function ktovar1(): HasOne
    {
        return $this->hasOne(ktovar1::class);
    }

    public function savdo1(): HasOne
    {
        return $this->hasOne(savdo1::class);
    }

    public function talmashish(): HasOne
    {
        return $this->hasOne(talmashish::class);
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
