<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;



class turharajat extends Model
{
    use HasFactory;
    protected $table='turharajat';

    public function boshqaharajat1(): HasOne
    {
        return $this->hasOne(boshqaharajat1::class);
    }

    public function chiqim_boshqa(): HasOne
    {
        return $this->hasOne(chiqim_boshqa::class);
    }

}
