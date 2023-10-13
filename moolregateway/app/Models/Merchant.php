<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class Merchant extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email','phone', 'password' ,'status', 'public_key', 'private_key'];
}
