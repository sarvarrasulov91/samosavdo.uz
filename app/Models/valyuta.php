<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class valyuta extends Model
{
    use HasFactory;
    protected $table='valyuta';

    public function boshqaharajat1(): HasOne
    {
        return $this->hasOne(boshqaharajat1::class);
    }

    public function ktovar1(): HasOne
    {
        return $this->hasOne(ktovar1::class);
    }

    public function chiqim(): HasOne
    {
        return $this->hasOne(chiqim::class);
    }

    public function kirim(): HasOne
    {
        return $this->hasOne(kirim::class);
    }

    public function kirim_dollar(): HasOne
    {
        return $this->hasOne(kirim_dollar::class);
    }
}
