<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Mijozlar extends Model
{
    use HasFactory;
    protected $table = 'mijozlar';
    protected $guarded = [];

    public function naqdsavdo1(): HasOne
    {
        return $this->hasOne(NaqdSavdo::class);
    }

    public function shartnoma1(): HasOne
    {
        return $this->hasOne(Shartnoma::class);
    }

    public function fond1(): HasOne
    {
        return $this->hasOne(FondSavdo::class);
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

    public function getFullNameAttribute()
    {
        return collect([
            $this->last_name,
            $this->first_name,
            $this->middle_name
        ])->filter()->implode(' ');
    }

    public function getFullAddressAttribute()
    {
        return collect([
            optional($this->tuman)->name_oz,
            optional($this->mfy)->name_oz,
            $this->manzil
        ])->filter()->implode(' ');
    }


}
