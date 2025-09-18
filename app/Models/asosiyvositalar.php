<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class asosiyvositalar extends Model
{
    use HasFactory;
    protected $table = 'asosiyvositalar';

    protected $fillable = [
        'kun',
        'tur_id',
        'brend_id',
        'tmodel_id',
        'shtrix_kod',
        'valyuta_id',
        'narhi',
        'snarhi',
        'valyuta_narhi',
        'tannarhi',
        'pastavshik_id',
        'xis_oyi',
        'user_id',
        'filial_id',
        'kirim_id',

        // qolgan maydonlar
    ];

    public function tur(): BelongsTo
    {
        return $this->belongsTo(tur::class);
    }

    public function brend(): BelongsTo
    {
        return $this->belongsTo(brend::class);
    }

    public function tmodel(): BelongsTo
    {
        return $this->belongsTo(tmodel::class);
    }

    public function valyuta(): BelongsTo
    {
        return $this->belongsTo(valyuta::class);
    }

    public function pastavshik(): BelongsTo
    {
        return $this->belongsTo(pastavshik::class);
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(filial::class);
    }
    
    public function lavozim(): BelongsTo
    {
        return $this->belongsTo(lavozim::class);
    }

}
