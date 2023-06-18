<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class DriverController extends Controller
{
    //
    public function register_driver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', Rule::unique('drivers')->where('status', 'Active')],
            'password' => 'required',
            'phone_no' => 'required',
            'image' => ['required', Rule::imageFile()],
            'restaurant_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/driver/' . $_FILES['image']['name'])) {
                    $driver = new Driver();
                    $driver->email = $request->email;
                    $driver->password = Hash::make($request->password);
                    $driver->phone_no = $request->phone_no;
                    $driver->name = $request->name;
                    $driver->image = $_FILES['image']['name'];
                    $driver->restaurant_id = $request->restaurant_id;
                    $driver->status = "Pending";
                    if ($driver->save()) {
                        return response()->json([
                            "message" => "Driver Registered successfully"
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
    public function login_driver(Request $request)
    {
        # code...
        $v = Validator::make($request->all(), array(
            'email' => 'required|email',
            'password' => 'required',
        ));
        if ($v->fails()) {
            return response()->json([
                $v->errors()
            ], 302);
        }

        // dd($request);
        $user = Driver::where('email', $request->email);
        if ($user->count() > 0) {
            $user = $user->first();
            $password = $user['password'];
            $check = $user::where('password', Hash::check($request->password, $password));
            if ($check->count() == 0) {
                if($user['status'] != 'Active'){
                    return response()->json([
                        'message'=>'Your information cannot be approved by Admin'
                    ],302);
                }
                Session::put('id', $user['id']);
                Session::put('name', $user['name']);
                return response()->json([
                    "message" => "Login Successfully"
                ], 200);
            } else {
                return response()->json([
                    'message' => "Password not verify try again !"
                ], 302);
            }
        } else {
            return response()->json([
                'message' => "Email not exists"
            ], 302);
        }
    }

    public function orders($id = null)
    {
        if (!$id) {
            $order = Orders::where('status','Ready for collection');
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
        } else {
            $order = Orders::where('status', 'Ready for collection')->where('id', $id);
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


    public function order_update_status($id, Request $request)
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
            if($request->status == 'Collected' || $request->status == 'Driver on their way'){
                DriverOrder::create([
                    'driver_id'=>session()->get('id'),
                    'order_id'=>$id,
                ]);
            }
            return response()->json([
                "message" => "Order " . $request->status . " updated successfully",
            ], 200);
        } else {
            return response()->json([
                "message" => "Something Went wrong",
            ], 200);
        }
    }


}
