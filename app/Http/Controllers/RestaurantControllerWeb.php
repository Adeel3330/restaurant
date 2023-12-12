<?php

namespace App\Http\Controllers;

use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;
use App\Models\RestaurantsTimings;
use App\Models\RestaurantTimeItems;
use App\Models\UsersAdditionals;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RestaurantControllerWeb extends Controller
{
    //
    public function restaurant_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'image' => ['required', Rule::imageFile()],
            'longitude' => 'required',
            'latitude' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
            'week_ids' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/restaurants/' . $_FILES['image']['name'])) {
                    $category = new Restaurants();
                    $category->name = $request->name;
                    $category->image = $_FILES['image']['name'];
                    $category->longitude = $request->longitude;
                    $category->latitude = $request->latitude;
                    $category->address = $request->address;
                    $category->phone_no = $request->phone_no;
                    $category->status = "Active";
                    if ($category->save()) {
                        $id = Restaurants::where('status', 'Active')->orderBy('id', 'desc')->first()['id'];
                        // dd($request->week_ids);
                        foreach ($request->week_ids as $week) {
                            $items = RestaurantTimeItems::create([
                                'restaurant_id' => $id,
                                'restaurant_timings_id' => $week
                            ]);
                        }
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
                $delete = RestaurantTimeItems::where('restaurant_id', $id)->delete();

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
            $restaurants = Restaurants::where('status', '!=', 'delete')->get();
            return view('/admin/restaurants', compact('restaurants'));
        } else {
            $restaurants = Restaurants::where('status', '!=', 'delete')->where('id', $id)->get();
            return view('/admin/restaurants', compact('restaurants'));
        }
    }



    public function edit_restaurant(Request $request, $id)
    {
        if (Restaurants::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                    'longitude' => ['required'],
                    'latitude' => ['required'],
                    'address' => 'required',
                    'phone_no' => 'required',
                    'week_ids' => 'required',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'longitude' => ['required'],
                    'latitude' => ['required'],
                    'address' => 'required',
                    'phone_no' => 'required',
                    'week_ids' => 'required',
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            
            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
                if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                    if ($_FILES['image']['size'] > 2000000) {
                        return response()->json([
                            "message" => "Max file size is 2mb"
                        ], 302);
                    }
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/restaurants/' .  Restaurants::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/restaurants/' . $_FILES['image']['name'])) {
                        $category = restaurants::where('id', $id)->update([
                            'name' => $request->name,
                            'image' => $_FILES['image']['name'],
                            'longitude' => $request->longitude,
                            'latitude' => $request->latitude,
                        ]);
                        if ($category) {
                            RestaurantTimeItems::where('restaurant_id', $id)->delete();
                            foreach ($request->week_ids as $week) {
                                $items = RestaurantTimeItems::create([
                                    'restaurant_id' => $id,
                                    'restaurant_timings_id' => $week
                                ]);
                            }
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
                        RestaurantTimeItems::where('restaurant_id', $id)->delete();
                        foreach ($request->week_ids as $week) {
                            $items = RestaurantTimeItems::create([
                                'restaurant_id' => $id,
                                'restaurant_timings_id' => $week
                            ]);
                        }
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

    public function get_distance()
    {
        $sid = Session::get('id');
        $user = UsersAdditionals::where('user_id', $sid)->where('status', 'Active');
        if ($user->count() <= 0) {
            return response()->json([
                "message", "Please insert location first"
            ], 302);
        }
        $user = $user->first();
        $restaurants = Restaurants::where('status', 'Active')->get();
        $rest_arr = [];
        foreach ($restaurants as $restaurant) {

            $distance = $this->distance($restaurant['latitude'], $restaurant['longitude'], $user['latitude'], $user['longitude'], "K");
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

    public function restaurant_edit($id)
    {
        $restaurant  = Restaurants::where('id', $id)->with('week_ids')->where('status', 'Active')->first();
        $weeks = RestaurantsTimings::all();

        return view('admin.restaurant-edit', compact('restaurant', 'weeks'));
    }

    public function restaurant_create_view()
    {
        $weeks = RestaurantsTimings::all();
        return view('/admin/restaurant-create', compact('weeks'));
    }


    public function update_restaurant_status($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        if (Restaurants::where('id', $id)->where('status', 'Pending')->count() > 0) {
            $driver = Restaurants::where('id', $id)->update([
                'status' => $request->status,
            ]);
            if ($driver) {
                return response()->json([
                    "message" => "Driver " . $request->status . " successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Driver not found or already deleted"
            ], 302);
        }
    }
}
