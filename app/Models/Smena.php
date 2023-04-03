<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smena extends Model
{
    use HasFactory;

    public $fillable = [
        'id',
        'start',
        'end',
        'active',
        'user_id'
    ];
}
