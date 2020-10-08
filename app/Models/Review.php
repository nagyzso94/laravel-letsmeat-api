<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
      'restaurant_id',
      'user_id',
      'savouriness',
      'prices',
      'service',
      'cleanness',
      'other_aspect'
    ];
}
