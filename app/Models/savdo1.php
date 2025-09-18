<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class savdo1 extends Model
{
    use HasFactory;

    protected $table = [];

    protected $guarded = [];

    function __construct($filialid=null, array $attributes = [] )
    {
        parent::__construct($attributes);
        $this->setTable('savdo' . ($filialid==null ? Auth::user()->filial_id : $filialid));
    }

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

}
