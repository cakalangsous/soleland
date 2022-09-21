<?php

namespace App\Models;

use App\Enums\CharacterGender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Character extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'salt',
    ];

    protected $cast = [
        'gender' => CharacterGender::class
    ];
}
