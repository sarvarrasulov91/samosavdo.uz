<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MijozlarIshJoy extends Model
{
    use HasFactory;
    protected $table = 'mijozlar_ish_joy';
    protected $guarded = [];


    public function shartnoma1(): HasOne
    {
        return $this->hasOne(shartnoma1::class);
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(filial::class);
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sms(): HasMany
    {
        return $this->hasMany(sms::class);
    }
}
