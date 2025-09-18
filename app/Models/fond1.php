<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class fond1 extends Model
{
    use HasFactory;
    protected $table = [];

    function __construct($filialid=null, array $attributes = [] )
    {
        parent::__construct($attributes);
        $this->setTable('fond' . ($filialid==null ? Auth::user()->filial_id : $filialid));
    }

    public function fond(): BelongsTo
    {
        return $this->belongsTo(fond::class);
    }

    public function mijozlar(): BelongsTo
    {
        return $this->belongsTo(mijozlar::class);
    }



}
