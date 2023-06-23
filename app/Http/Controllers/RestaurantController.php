<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\DriverOrder;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\UsersAdditionals;
use App\Models\RestaurantsTimings;
use Illuminate\Support\Facades\Session;
use App\Models\Driver;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    //
    public function restaurant_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('restaurants')],
            'image' => ['required', Rule::imageFile()],
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'phone_no' => 'required',
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
                    $category->status = "Pending";
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
            $category = Restaurants::where('status', '!=','delete');
            if ($category->count() > 0) {

                return response()->json(
                    $category->with('week_ids')->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No restaurant found"
                ], 302);
            }
        } else {
            $category = Restaurants::where('status','!=', 'delete')->where('id', $id);
            if ($category->count() > 0) {

                return response()->json(
                    $category->with('week_ids')->get(),
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
                    'address'=>'required',
                    'phone_no'=>'required',
                    'week_id'=>'required'
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
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/image/restaurants' .  Restaurants::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/restaurants' . $_FILES['image']['name'])) {
                        $category = restaurants::where('id', $id)->update([
                            'name' => $request->name,
                            'image' => $_FILES['image']['name'],
                            'longitude' => $request->longitude,
                            'latitude' => $request->latitude,
                            'address' => $request->address,
                            'phone_no' => $request->phone_no,
                            'week_id' => $request->week_id,
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
                        'address' => $request->address,
                        'phone_no' => $request->phone_no,
                        'week_id' => $request->week_id,
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

    public function restaurant_week_timings()
    {
        $weeks = RestaurantsTimings::all();
        if($weeks->count() > 0){
            return response()->json($weeks, 200);
        }
        else{
            return response()->json([
                'message'=>'No timing Found'
            ], 302);
        }
      

    }


    public function orders($id = null)
    {
        $sid = session()->get('restaurant_id');
        if (!$id) {
            $order = Orders::where('status', '!=','delete');
            if ($order->count() > 0) {
                $orders = $order->where('restaurant_id',$sid)->with('user')->get();
                foreach ($orders as $order) {
                    $order_items = OrderItems::where('order_id', $order['id'])->with('product')->get();
                    $order['orders_items'] = $order_items;
                }
                return response()->json($orders, 200);
            } else {
                return response()->json([
                    "message" => "No Order found"
                ], 302);
            }
        } else {
            $order = Orders::where('status', '!=', 'delete')->where('id', $id);
            if ($order->count() > 0) {
                $orders = $order->with('user')->get();
                foreach ($orders as $order) {
                    $order_items = OrderItems::where('order_id', $order['id'])->with('product')->get();
                    $order['orders_items'] = $order_items;
                }
                return response()->json($orders, 200);
            } else {
                return response()->json([
                    "message" => "No Order found"
                ], 302);
            }
        }
    }

    public function order_update_status($id,Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        if (!$id) {
            return response()->json([
                "message" => "Please Enter Id of Order for update",
            ], 302);
        }
        $order = Orders::where('id', $id)->update([
            'status' => $request->status,
        ]);
        if ($order) {
            return response()->json([
                "message" => "Order " . $request->status . " updated successfully",
            ], 200);
        } else {
            return response()->json([
                "message" => "Something Went wrong",
            ], 200);
        }
    }

    public function get_drivers(){
        $drivers = Driver::where('status','Active');
        if($drivers->count() > 0){
            return response()->json($drivers->get(),200);
        }
        return response()->json(['message'=>'No driver found'],200);
    }

    public function assign_driver(Request $request){
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'driver_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }

        if (DriverOrder::where('order_id', $request->id)->where('driver_id', $request->driver_id)->count() <= 0) {
            $driverAssign = DriverOrder::create([
                'driver_id' => $request->driver_id,
                'order_id' => $request->order_id,
            ]);
            if($driverAssign){
                return response()->json([
                    'message' => 'Driver Assign Successfully',
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'Something went wrong',
                ], 302);
            }
        } else {
            return response()->json([
                'message'=>'Order Already Exists',
            ],302);
        }
    }
}
