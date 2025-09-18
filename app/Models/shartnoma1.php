<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasOne;

class shartnoma1 extends Model
{
    use HasFactory;

    protected $table = [];

    function __construct($filialid=null, array $attributes = [] )
    {
        parent::__construct($attributes);
        $this->setTable('shartnoma' . ($filialid==null ? Auth::user()->filial_id : $filialid));
    }


    public function mijozlar(): BelongsTo
    {
        return $this->belongsTo(mijozlar::class);
    }
    
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tashrif(): BelongsTo
    {
        return $this->belongsTo(tashrif::class);
    }

    public function tulovlar1(): HasOne
    {
        return $this->hasOne(tulovlar1::class, 'shartnomaid', 'id');
    }





}
