<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class KirimTovar extends Model
{
    use HasFactory;
    protected $table = 'kirim_tovar';

//    function __construct($filialid=null, array $attributes = [] )
//    {
//        parent::__construct($attributes);
//        $this->setTable('ktovar' . ($filialid==null ? Auth::user()->filial_id : $filialid));
//    }

    protected $guarded = [];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
