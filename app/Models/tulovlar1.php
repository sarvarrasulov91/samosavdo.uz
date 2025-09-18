<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class tulovlar1 extends Model
{
    use HasFactory;
    protected $table = [];
    
    protected $fillable = [
        'kun',
        'tulovturi',
        'shartnomaid',
        'naqd',
        'pastik',
        'hr',
        'click',
        'avtot',
        'chegirma',
        'umumiysumma',
        'user_id',
    ];

    function __construct($filialid=null, array $attributes = [] )
    {
        parent::__construct($attributes);
        $this->setTable('tulovlar' . ($filialid==null ? Auth::user()->filial_id : $filialid));
    }

    public function shartnoma1(): BelongsTo
    {
        return $this->belongsTo(shartnoma1::class,'shartnomaid');
    }
    
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
