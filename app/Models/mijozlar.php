<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class mijozlar extends Model
{
    use HasFactory;
    protected $table = 'mijozlar';
    protected $guarded = [];

    public function naqdsavdo1(): HasOne
    {
        return $this->hasOne(naqdsavdo1::class);
    }

    public function shartnoma1(): HasOne
    {
        return $this->hasOne(shartnoma1::class);
    }

    public function fond1(): HasOne
    {
        return $this->hasOne(fond1::class);
    }

    public function viloyat(): BelongsTo
    {
        return $this->belongsTo(viloyat::class);
    }

    public function tuman(): BelongsTo
    {
        return $this->belongsTo(tuman::class);
    }

    public function mfy(): BelongsTo
    {
        return $this->belongsTo(mfy::class);
    }
    
    public function filial(): BelongsTo
    {
        return $this->belongsTo(filial::class);
    }
    
    public function ish_joy(): BelongsTo
    {
        return $this->belongsTo(ish_joy::class);
    }
    
    public function ish_tashkiloti(): BelongsTo
    {
        return $this->belongsTo(ish_tashkiloti::class);
    }


}
