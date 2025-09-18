<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class chiqim_boshqa extends Model
{
    use HasFactory;
    protected $table = 'chiqim_boshqa';

    public function valyuta(): BelongsTo
    {
        return $this->belongsTo(valyuta::class);
    }

    public function turharajat(): BelongsTo
    {
        return $this->belongsTo(turharajat::class);
    }

}
