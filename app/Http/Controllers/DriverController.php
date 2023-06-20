<?php

namespace App\Http\Controllers;

use App\Models\otps;
use App\Models\Driver;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\DriverOrder;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class DriverController extends Controller
{
    //
    public function register_driver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', Rule::unique('drivers')],
            'password' => ['required', Password::min(6)->numbers()->mixedCase()],
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
                        'message'=> 'Account Verification Pending'
                    ],302);
                }
                Session::put('driver_id', $user['id']);
                Session::put('driver_name', $user['name']);
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
            $order = DriverOrder::where('driver_id',session()->get('driver_id'));
            // $order = Orders::where('status','Ready for collection');
            if ($order->count() > 0) {
                $orders = $order->with('order')->get();
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
            $order = DriverOrder::where('driver_id', session()->get('driver_id'))->where('id',$id);
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
            return response()->json([
                "message" => "Order " . $request->status . " updated successfully",
            ], 200);
        } else {
            return response()->json([
                "message" => "Something Went wrong",
            ], 200);
        }
    }
    
    public function restaurants_driver(){
        $category = Restaurants::where('status', 'Active');
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

    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if (Driver::where('email', $request->email)->count() > 0) {
                return $this->basic_email($request->email, "OTP send successfully");
            } else {
                return response()->json([
                    "message" => "Email not exists"
                ], 302);
            }
        }
    }



    public function basic_email($emails, $messages)
    {
        $otp = rand(1000, 9999);
        $uname = Driver::where('email', $emails)->where('status', 'Active');
        if($uname->count() <= 0){
            return response()->json([
                'message'=>'Account Verification Pending'
            ],302);
        }
        $uname = $uname->first();
        $name = $uname['name'];
        $data = array('otp' => $otp, 'name' => $name);
        $email = Mail::send('mail', $data, function ($message) use ($emails) {
            $message->to($emails, config('app.name'))->subject('OTP Message');
            $message->from('testuser1447@gmail.com', config('app.name'));
        });
        if ($email) {
            $otp_update = otps::where('email', $emails)->update(['verified' => 'Yes']);
            $otp_create = otps::create(['email' => $emails, 'verified' => 'No', 'otp' => $otp]);
            Session::put('otp_email', $emails);
            return response()->json([
                "message" => $messages
            ], 200);
        } else {
            return response()->json([
                "message" => "Something Went Wrong send mail"
            ], 302);
        }
    }




    public function otp_verified(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            $otp_email = Session::get('otp_email');
            $otp_verified = otps::where('otp', $request->otp)->where('email', $otp_email)->where('verified', 'No')->count();
            if ($otp_verified > 0) {
                return response()->json([
                    "message" => "Otp verified"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Otp not verified try again!"
                ], 302);
            }
        }
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', Password::min(6)->numbers()->mixedCase()],
            'confirm_password' => ['required', 'same:password', Password::min(6)->numbers()->mixedCase()],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            $email = Session::get('otp_email');
            $user = Driver::where('email', $email)->where('status', 'Active')->first();
            // dd(Users::where('email', $email)->where('status', 'Active')->first());
            if (Hash::check($request->password, $user['password'])) {
                return response()->json([
                    "message" => "You cannot use your old password"
                ], 302);
            } else {
                // dd(Hash::make($request->password));
                $user_update = $user->update(['password' => Hash::make($request->password)]);
                if ($user_update) {
                    return response()->json([
                        "message" => "Your password was updated"
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "Something went wrong"
                    ], 302);
                }
            }
        }
    }

    public function cookies_get()
    {
        return response()->json($_COOKIE, 200);
    }

    public function get_otp_email()
    {
        $arr = Session::all();
        return response()->json($arr, 200);
    }

    public function insert_location(Request $request){
        $validator = Validator::make($request->all(), [
            'longitude' => ['required'],
            'latitude' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        $sid = session()->get('driver_id');
         $driver = Driver::where('id',$sid)->where('status','Active');
        if($driver->count() > 0){
            $driver = $driver->update([
                'longitude'=>$request->longitude,
                'latitude'=>$request->latitude
            ]);
            if($driver){
                return response()->json([
                    'message'=>'Location updated against driver',
                ],200);
            }
            else{
                return response()->json([
                    'message' => 'Something went wrong',
                ], 200);
            }
        }
        else{
            return response()->json([
                'message' => 'Driver not exists',
            ], 200);
        }

       
    }

}
