<?php

namespace App\Http\Controllers;

use App\Models\otps;
use App\Models\RestaurantOwner;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RestaurantOwnerController extends Controller
{
    //
    public function restaurant_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required','email', Rule::unique('restaurants_owner')],
            'restaurant_id' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
               $restaurant = new RestaurantOwner();
        
                    $restaurant->email = $request->email;
                    $restaurant->restaurant_id = $request->restaurant_id;
                    $restaurant->password = Hash::make($request->password);
                    $restaurant->status = "Pending";
                    if ($restaurant->save()) {
                        return response()->json([
                            "message" => "Restaurant created successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
        }
    }


    public function login_restaurant(Request $request)
    {
        $v = Validator::make($request->all(), array(
            'email' => 'required|email',
            'password' => 'required',
        ));
        if ($v->fails()) {
            return response()->json([
                $v->errors()
            ], 302);
        }
        $user = RestaurantOwner::where('email', $request->email);
        if ($user->count() > 0) {
            $user = $user->first();
            $password = $user['password'];
            $check = $user::where('password', Hash::check($request->password, $password));
            if ($check->count() == 0) {
                if ($user['status'] != 'Active') {
                    return response()->json([
                        'message' => 'Account Verification Pending'
                    ], 302);
                }
                Session::put('restaurant_id', $user['restaurant_id']);
                Session::put('email', $user['email']);
                // Session::put('name', $u);
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


    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if (RestaurantOwner::where('email', $request->email)->count() > 0) {
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
        $uname = RestaurantOwner::where('email', $emails)->where('status', 'Active');
        if ($uname->count() <= 0) {
            return response()->json([
                'message' => 'Account Verification Pending'
            ], 302);
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
            Session::put('otp_email_restaurant', $emails);
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
            $otp_email = Session::get('otp_email_restaurant');
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
            $email = Session::get('otp_email_restaurant');
            $user = RestaurantOwner::where('email', $email)->where('status', 'Active')->first();
            // dd(Users::where('email', $email)->where('status', 'Active')->first());
            if (Hash::check($request->password, $user['password'])) {
                return response()->json([
                    "message" => "You cannot use your old password"
                ], 302);
            } else {
                // dd(Hash::make($request->password));
                $user_update = RestaurantOwner::where('email', $email)->where('status', 'Active')->update(['password' => Hash::make($request->password)]);
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

    public function restaurants($id = null)
    {
        if (!$id) {
            $category = Restaurants::where('status', '!=', 'delete');
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
            $category = Restaurants::where('status', '!=', 'delete')->where('id', $id);
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
}
