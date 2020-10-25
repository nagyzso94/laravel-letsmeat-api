<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;

class RestaurantController extends Controller
{
    public function index()
    {
        return Restaurant::all();
    }

    public function reviewcount()
    {
      return DB::table('reviews')
        ->select('restaurant_id',DB::raw('count(*) as count'))
        ->groupBy('restaurant_id')
        ->get();
    }

    public function create(Request $request)
    {
      $request->validate([
          'name' => 'required|string|unique:restaurants|max:20|min:2',
          'web_page' => 'required|string|unique:restaurants|active_url',
          'type' => 'required',
          'address' => 'required|string|min:2|unique:restaurants',
          'phone_number' => [
            'required',
            'string',
            'max:13',
            'regex:/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/']
      ]);

        $restaurant = new Restaurant([
          'name' => $request->name,
          'address' => $request->address,
          'phone_number' => $request->phone_number,
          'web_page' => $request->web_page,
          'type' => $request->type
        ]);

        $restaurant->save();

        return response()->json([
            "message" => "Restaurant saved successfully"
        ], 201);

    }

    public function show($id)
    {
        $restaurant = Restaurant::find($id);

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

        $avg_review = array(
          'savouriness' => (float)$savouriness_avg,
          'prices' => (float)$prices_avg,
          'service' => (float)$prices_avg,
          'cleanness' => (float)$cleanness_avg
          );

        return response()->json([
          'restaurant' => $restaurant,
          'avg_review' => $avg_review
          ]);
    }


    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::find($id);
        $restaurant -> update($request->all());
        return $restaurant;
    }

    public function destroy($id)
    {
        return Restaurant::destroy($id);
    }

}
