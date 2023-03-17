<?php

namespace App\Http\Controllers;

use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use App\Models\UsersAdditionals;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    //
    public function restaurant_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('restaurants')->where('status', 'Active')],
            'image' => ['required', Rule::imageFile()],
            'longitude'=>'required',
            'latitude'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], '../public/image/restaurants/' . $_FILES['image']['name'])) {
                    $category = new Restaurants();
                    $category->name = $request->name;
                    $category->image = $_FILES['image']['name'];
                    $category->longitude = $request->longitude;
                    $category->latitude = $request->latitude;
                    $category->status = "Active";
                    if ($category->save()) {
                        return response()->json([
                            "message" => "Restaurant created successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                } else {
                    return response()->json([
                        "message" => "File not move try again!"
                    ], 302);
                }
            }
        }
    }

    public function delete_restaurant($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of restaurant for deleting"
            ], 302);
        }
        if (Restaurants::where('id', $id)->where('status', 'Active')->count() > 0) {
            $category = Restaurants::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($category) {
                return response()->json([
                    "message" => "Restaurant deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Restaurant not found or already deleted"
            ], 302);
        }
    }
    public function restaurants($id = null)
    {
        if (!$id) {
            $category = Restaurants::where('status', 'Active');
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No restaurant found"
                ], 302);
            }
        } else {
            $category = Restaurants::where('status', 'Active')->where('id', $id);
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No Restaurant found"
                ], 302);
            }
        }
    }



    public function edit_restaurant(Request $request, $id)
    {
        if (Restaurants::where('status', 'Active')->where('id', $id)->count() > 0) {
            if ($_FILES) {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                    'longitude' => ['required'],
                    'latitude' => ['required'],
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'longitude' => ['required'],
                    'latitude' => ['required'],
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Restaurants::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
                return response()->json([
                    "message" => "The name has already been taken"
                ], 302);
            }
            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
                if ($_FILES) {
                    if ($_FILES['image']['size'] > 2000000) {
                        return response()->json([
                            "message" => "Max file size is 2mb"
                        ], 302);
                    }
                    unlink('../public/image/restaurants/' .  Restaurants::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], '../public/image/restaurants/' . $_FILES['image']['name'])) {
                        $category = restaurants::where('id', $id)->update([
                            'name' => $request->name,
                            'image' => $_FILES['image']['name'],
                            'longitude' => $request->longitude,
                            'latitude' => $request->latitude,
                        ]);
                        if ($category) {
                            return response()->json([
                                "message" => "Restaurant updated successfully"
                            ], 200);
                        } else {
                            return response()->json([
                                "message" => "Something went wrong"
                            ], 302);
                        }
                    } else {
                        return response()->json([
                            "message" => "File not move try again!"
                        ], 302);
                    }
                } else {
                    $category = Restaurants::where('id', $id)->update([
                        'name' => $request->name,
                        'longitude' => $request->longitude,
                        'latitude' => $request->latitude,
                    ]);
                    if ($category) {
                        return response()->json([
                            "message" => "Restaurant updated successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                }
            }
        } else {
            return response()->json([
                "message" => "Restaurant not exists"
            ], 302);
        }
    }

    public function get_distance(){
        $sid = Session::get('id');
        $user = UsersAdditionals::where('user_id',$sid)->where('status','Active');
        if($user->count() <= 0) {
            return response()->json([
                "message","Please insert location first"
            ], 302);
        }
        $user = $user->first();
        $restaurants = Restaurants::where('status','Active')->get();
        $rest_arr =[];
        foreach($restaurants as $restaurant){

            $distance = $this->distance($restaurant['latitude'],$restaurant['longitude'], $user['latitude'], $user['longitude'],"K");
            // return round($distance,2);
            // if($distance <= 30){
                $restaurant["distance"] = $distance;
                $rest_arr[] = $restaurant;
            // }
            
            // var_dump($restaurant);
        }
        return $rest_arr;



        // $get_location = Controller::distance();

    }



    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}
