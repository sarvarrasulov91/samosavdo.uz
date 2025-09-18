<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class tashrif extends Model
{
    protected $table = 'tashrif';
    use HasFactory;

    public function shartnoma1(): HasOne
    {
        return $this->hasOne(shartnoma1::class);
    }

}
