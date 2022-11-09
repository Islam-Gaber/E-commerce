<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $guarded = [];
    protected $table = 'users';
    public function isAdmin()
    {
        if ($this->type == 'admin') {
            return true;
        }
        return false;
    }
    public function isUser()
    {
        if ($this->type == 'user') {
            return true;
        }
        else{
            return false;
        }
    }
    public function isTrader()
    {
        if ($this->type == 'trader') {
            return true;
        }
        else{
            return false;
        }
    }
}
