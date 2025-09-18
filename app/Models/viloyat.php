<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;

class viloyat extends Model
{
    use HasFactory;
    protected $table='viloyat';

    public function tuman(): HasOne
    {
        return $this->hasOne(tuman::class);
    }


    public function mijozlar(): HasOne
    {
        return $this->hasOne(mijozlar::class);
    }


}
