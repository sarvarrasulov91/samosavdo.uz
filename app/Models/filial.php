<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class filial extends Model
{
    use HasFactory;
    protected $table = 'filial';

    public function kirim(): HasOne
    {
        return $this->hasOne(kirim::class);
    }

    public function talmashish(): HasOne
    {
        return $this->hasOne(talmashish::class);
    }

    public function ktovar1(): HasOne
    {
        return $this->hasOne(ktovar1::class);
    }

    public function tqaytarish(): HasOne
    {
        return $this->hasOne(tqaytarish::class);
    }

    public function tmqaytarish(): HasOne
    {
        return $this->hasOne(tmqaytarish::class);
    }
    
    public function mijozlar(): HasOne
    {
        return $this->hasOne(mijozlar::class);
    }

    public function chiqim_taminot(): HasOne
    {
        return $this->hasOne(chiqim_taminot::class);
    }
}
