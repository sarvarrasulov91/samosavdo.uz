<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\turharajat;
use App\Models\valyuta;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class boshqaharajat1 extends Model
{
    use HasFactory;

    protected $table=[];

    function __construct($filialid=null, array $attributes = [] )
    {
        parent::__construct($attributes);
        $this->setTable('boshqaharajat' . ($filialid==null ? Auth::user()->filial_id : $filialid));
    }

    public function valyuta(): BelongsTo
    {
        return $this->belongsTo(valyuta::class);
    }

    public function turharajat(): BelongsTo
    {
        return $this->belongsTo(turharajat::class);
    }



}
