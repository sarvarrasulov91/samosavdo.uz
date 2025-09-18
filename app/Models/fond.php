<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class fond extends Model
{
    use HasFactory;
    protected $table = 'fond';
    public function fond1(): HasOne
    {
        return $this->hasOne(fond1::class);
    }
}
