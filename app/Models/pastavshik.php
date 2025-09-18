<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class pastavshik extends Model
{
    use HasFactory;
    protected $table = 'pastavshik';
    protected $fillable=['pastav_name','manzili','telefoni','xis_raqami','inn','mfo','status','user_id'];

    public function ktovar1(): HasOne
    {
        return $this->hasOne(ktovar1::class);
    }

    public function talmashish(): HasOne
    {
        return $this->hasOne(talmashish::class);
    }

    public function chiqim(): HasOne
    {
        return $this->hasOne(chiqim::class);
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
