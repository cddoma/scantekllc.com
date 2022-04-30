<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'sessions';
    protected $keyType = 'string';
    protected $fillable = [
        'latest_activity',
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
    ];
}
