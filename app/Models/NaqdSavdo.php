<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NaqdSavdo extends Model
{
    use HasFactory;

    protected $table = 'naqd_savdo';

//    function __construct($filialid=null, array $attributes = [] )
//    {
//        parent::__construct($attributes);
//        $this->setTable('naqdsavdo' . ($filialid==null ? Auth::user()->filial_id : $filialid));
//    }


    public function mijozlar(): BelongsTo
    {
        return $this->belongsTo(Mijozlar::class);
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(filial::class);
    }


}
