<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Auth;
use App\Helpers\ApiHelpers;
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

      $request_validate = $request -> validate([
          'restaurant_id'=> ['required'],
          'user_id'=> ['required'],
          'savouriness' => ['required', 'regex:/^(([0-4]?(\.|\,)[0-9]+[0-9]*)|(5?(\.|\,)[0]+[0]*))$/'],
          'prices' => ['required', 'regex:/^(([0-4]?(\.|\,)[0-9]+[0-9]*)|(5?(\.|\,)[0]+[0]*))$/'],
          'service' => ['required', 'regex:/^(([0-4]?(\.|\,)[0-9]+[0-9]*)|(5?(\.|\,)[0]+[0]*))$/'],
          'cleanness' => ['required', 'regex:/^(([0-4]?(\.|\,)[0-9]+[0-9]*)|(5?(\.|\,)[0]+[0]*))$/'],
          'other_aspect' => ['string','max:100','nullable']
      ]);

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

      $review_save = $review->save();

  /*    if ($review_save) {
        $response = ApiHelpers::createApiResponse(false,201,'Review added successfully', null);
        return response()->json($response,200);
      } else {

        $response = ApiHelpers::createApiResponse(true,400,'Review creating failed', null);
        return response()->json($response,400);
      }*/

      return response()->json([
          "message" => "Review saved successfully",
          "code" => 201,
          "data" => $review
      ], 201);
    }

    public function show($id)
    {
        return DB::table('reviews')
          ->join('users', 'users.id', '=', 'reviews.user_id')
          ->join('restaurants', 'restaurants.id', '=', 'reviews.restaurant_id')
          ->select('reviews.savouriness','reviews.prices','reviews.service','reviews.cleanness','reviews.other_aspect', 'users.name as userName','users.id as userId', 'restaurants.name as restaurantName','restaurants.id as restaurantId')
          ->where('restaurant_id','=',$id)
          -> orderByDesc('reviews.id')
          ->get();
    }


    public function showuser($id)
    {
        return DB::table('reviews')
          -> where('user_id','=',$id)
          -> join('restaurants', 'restaurants.id', '=', 'reviews.restaurant_id')
          -> join('users', 'users.id', '=', 'reviews.user_id')
          -> select('reviews.savouriness','reviews.prices','reviews.service','reviews.cleanness','reviews.other_aspect', 'users.name as userName','users.id as userId', 'restaurants.name as restaurantName','restaurants.id as restaurantId')
          -> orderByDesc('reviews.id')
          -> get();
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
