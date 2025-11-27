<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tulovlar extends Model
{
    use HasFactory;
    protected $table = 'tulovlar';

    protected $guarded = [];

//    function __construct($filialid=null, array $attributes = [] )
//    {
//        parent::__construct($attributes);
//        $this->setTable('tulovlar' . ($filialid==null ? Auth::user()->filial_id : $filialid));
//    }

    public function shartnoma(): BelongsTo
    {
        return $this->belongsTo(shartnoma::class,'shartnoma_id');
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
