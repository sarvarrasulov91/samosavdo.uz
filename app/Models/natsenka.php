<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class natsenka extends Model
{
    use HasFactory;
    protected $table='natsenka';

    public function brend(): HasOne
    {
        return $this->hasOne(brend::class);
    }
    
    public function tur(): HasOne
    {
        return $this->hasOne(tur::class);
    }

}

