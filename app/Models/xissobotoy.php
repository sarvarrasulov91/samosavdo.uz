<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class xissobotoy extends Model
{
    use HasFactory;
    protected $table = 'xissobotoy';
    protected $fillable = [
        'xis_oy',
        'user_id',
        'foiz'];
}
