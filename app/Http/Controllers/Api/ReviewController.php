<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\User;
use App\Models\Restaurant;

class ReviewController extends Controller
{

    public function index()
    {
        return Review::all();
    }

    public function create(Request $request)
    {

      $review = new Review([
        'savouriness'=> $request->savouriness,
        'prices'=> $request->prices,
        'service'=> $request->service,
        'cleanness'=> $request->cleanness,
        'other_aspect'=> $request->other_aspect
      ]);

      $user = User::findOrFail($request->user_id);
      $review->user()->associate($user);

      $restaurant = Restaurant::findOrFail($request->restaurant_id);
      $review->restaurant()->associate($restaurant);

      $request->validate([
          'restaurant_id'=> 'required',
          'user_id'=> 'required',
          'savouriness' => 'required|min:0|max:5',
          'prices' => 'required|min:0|max:5',
          'service' => 'required|min:0|max:5',
          'cleanness' => 'required|min:0|max:5',
          'other_aspect' => 'required|string|max:100'
      ]);

      $review->save();

      return response()->json([
          "message" => "Restaurant saved successfully",
          "user" => $user,
          "étterem" => $restaurant
      ], 201);
    }

    public function show($id)
    {
        return DB::table('reviews')
                    ->join('users', 'users.id', '=', 'reviews.user_id')
                    ->join('restaurants', 'restaurants.id', '=', 'reviews.restaurant_id')
                    ->select('reviews.savouriness','reviews.prices','reviews.service','reviews.cleanness','reviews.other_aspect', 'users.name as userName','users.id as userId', 'restaurants.name as restaurantName','restaurants.id as restaurantId')
                    ->where('restaurant_id','=',$id)
                    ->get();
    }


    public function showuser($id)
    {
        $userReviews = DB::table('reviews')
          -> where('user_id','=',$id)
          -> join('restaurants', 'restaurants.id', '=', 'reviews.restaurant_id')
          -> join('users', 'users.id', '=', 'reviews.user_id')
          -> select('reviews.savouriness','reviews.prices','reviews.service','reviews.cleanness','reviews.other_aspect', 'users.name as userName','users.id as userId', 'restaurants.name as restaurantName','restaurants.id as restaurantId')
          -> get();

        return $userReviews;
    }

    public function update(Request $request, $id)
    {
      $review = Review::find($id);
      $review -> update($request->all());
      return $review;
    }

    public function destroy($id)
    {
        return Review::destroy($id);
    }

    public function statistics($id)
    {
        $savouriness_avg = DB::table('reviews')
          -> where('restaurant_id','=',$id)
          -> avg('savouriness');

        $prices_avg = DB::table('reviews')
          -> where('restaurant_id','=',$id)
          -> avg('prices');

        $service_avg = DB::table('reviews')
          -> where('restaurant_id','=',$id)
          -> avg('service');

        $cleanness_avg = DB::table('reviews')
          -> where('restaurant_id','=',$id)
          -> avg('cleanness');

        return response()->json([
          'savouriness' => (float)$savouriness_avg,
          'prices' => (float)$prices_avg,
          'service' => (float)$prices_avg,
          'cleanness' => (float)$cleanness_avg]);

    }


}
