<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class kirimtur extends Model
{
    use HasFactory;
    protected $table = 'kirimtur';

    public function kirim(): HasOne
    {
        return $this->hasOne(kirim::class);
    }

}
