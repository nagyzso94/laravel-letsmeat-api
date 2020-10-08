<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Auth;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{

    public function index()
    {
        return Review::all();
    }


    public function bla()
    {
    }

    public function create(Request $request)
    {

      $review = new Review([
        'restaurant_id'=> $request->restaurant_id,
        'user_id'=> $request->id,
        'savouriness'=> $request->savouriness,
        'prices'=> $request->prices,
        'service'=> $request->service,
        'cleanness'=> $request->cleanness,
        'other_aspect'=> $request->other_aspect
      ]);

      $request->validate([
          //'restaurant_id'=> 'required',
          //'user_id'=> 'required',
          'savouriness' => 'required|min:0|max:5',
          'prices' => 'required|min:0|max:5',
          'service' => 'required|min:0|max:5',
          'cleanness' => 'required|min:0|max:5',
          'other_aspect' => 'required|string|max:100',

      ]);

      $review->save();

      return response()->json([
          "message" => "Restaurant saved successfully"
      ], 201);
    }

    public function show($id)
    {
        return Review::find($id);
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
}
