<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'address',
      'phone_number',
      'web_page',
      'type'
    ];

    //Get the reviews for the restaurant
    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

}
