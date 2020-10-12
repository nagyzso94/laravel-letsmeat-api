<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index()
    {
        return Restaurant::all();
    }

    public function create(Request $request)
    {
        $restaurant = new Restaurant([
          'name' => $request->name,
          'address' => $request->address,
          'phone_number' => $request->phone_number,
          'web_page' => $request->web_page,
          'type' => $request->type
        ]);

        $request->validate([
            'name' => 'required|string|unique:restaurants|max:20',
            'web_page' => 'required|string|unique:restaurants|active_url',
            'type' => 'required',
            'address' => 'required|string|unique:restaurants',
            'phone_number' => [
              'required',
              'string',
              'max:13',
              'regex:/((?:\+?3|0)6)(?:-|\()?(\d{1,2})(?:-|\))?(\d{3})-?(\d{3,4})/']
        ]);

        $restaurant->save();

      //  return response()->json([
      //      "restaurant"=>$restaurant
      //  ], 201);

        return response()->json([
            "message" => "Restaurant saved successfully"
        ], 201);

    }

    public function show($id)
    {
        return Restaurant::find($id);
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
