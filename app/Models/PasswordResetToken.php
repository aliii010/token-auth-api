<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens'; // default table created by sanctum

    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];
}
