<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tuman extends Model
{
    use HasFactory;
    protected $table='tuman';

    public function viloyat(): BelongsTo
    {
        return $this->belongsTo(viloyat::class);
    }

    public function mijozlar(): HasOne
    {
        return $this->hasOne(mijozlar::class);
    }

    public function mfy(): HasOne
    {
        return $this->hasOne(mfy::class);
    }

}
