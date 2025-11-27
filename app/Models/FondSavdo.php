<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FondSavdo extends Model
{
    use HasFactory;
    protected $table = 'fond_savdo';

//    function __construct($filialid=null, array $attributes = [] )
//    {
//        parent::__construct($attributes);
//        $this->setTable('fond' . ($filialid==null ? Auth::user()->filial_id : $filialid));
//    }

    public function fond(): BelongsTo
    {
        return $this->belongsTo(fond::class);
    }

    public function mijozlar(): BelongsTo
    {
        return $this->belongsTo(Mijozlar::class);
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(filial::class);
    }



}
