<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class transport extends Model
{
    use HasFactory;
    protected $table='transport';

    public function tur(): HasOne
    {
        return $this->hasOne(tur::class);
    }

}
