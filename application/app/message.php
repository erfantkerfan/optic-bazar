<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    protected $fillable = [
        'send_id', 'user_id', 'message', 'status',
    ];
}
