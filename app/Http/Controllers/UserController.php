<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\otps;
use env;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    //
    public function Login(Request $request)
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
        $user = Users::where('email', $request->email);
        if ($user->count() > 0) {
            $user = $user->first();
            $password = $user['password'];
            $check = $user::where('password', Hash::check($request->password, $password));
            if ($check->count() == 0) {
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


    function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->where('status', 'Active')],
            'phone_no' => 'required',
            'password' => ['required', Password::min(6)->numbers()->mixedCase()],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            $user = new users;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->password = Hash::make($req->password);
            $user->phone_no = $req->phone_no;
            $user->status = 'Active';
            $user->image= 'Null';
            $res = $user->save();
            if ($res) {
                return response()->json([
                    'message' => "User Created successfully"
                ], 200);
            } else {
                return response()->json([
                    'message' => "Something went wrong"
                ], 302);
            }
        }
    }


    function getusers($id = null)
    {
        if (!isset($id) && empty($id)) {
            $users = new users;
            $get = users::where('status', 'Active');
            if ($get->count() > 0) {
                $usersresult = $get->get();
                return response()->json($usersresult, 200);
            } else {
                return response()->json([
                    'message' => "No Records Found"
                ], 302);
            }
        } else {
            // return $id;
            $users = new users;
            $get = users::where('id', $id)->where('status', 'Active');
            if ($get->count() > 0) {
                $usersresult = $get->get();
                return response()->json($usersresult, 200);
            } else {
                return response()->json([
                    'message' => "No Records Found"
                ], 302);
            }
        }
        // return $usersresult;
    }


    function deleteuser($id)
    {

        $users = users::where('id', $id)->where('status', 'Active');
        if ($users->count() == 1) {
            $update = $users->update(['status' => 'delete']);
            if ($update) {
                return response()->json([
                    'message' => "User Delete successfully"
                ], 200);
            } else {
                return response()->json([
                    'message' => "Something Went wrong"
                ], 302);
            }
        } else {
            return response()->json([
                'message' => "Invalid Id or record already deleted"
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
            if (Users::where('email', $request->email)->count() > 0) {
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
        $uname = Users::where('email', $emails)->where('status', 'Active')->first();
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
            $user = Users::where('email', $email)->where('status', 'Active')->first();
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

    public function AdminLogin(Request $request){

        $v = Validator::make($request->all(), array(
            'email' => 'required|email',
            'password' => 'required',
        ));
        if ($v->fails()) {
            return response()->json([
                $v->errors()
            ], 302);
        }


        $user = Admins::where('email', $request->email);
        if ($user->count() > 0) {
            $user = $user->first();
            $password = $user['password'];
            $check = $user::where('password', Hash::check($request->password, $password));
            if ($check->count() == 0) {
                Session::put('admin_id', $user['id']);
                Session::put('admin_name', $user['name']);
                return response()->json([
                    "message" => "Admin Login Successfully"
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

    public function cookies_get(){
        return response()->json($_COOKIE,200);
    }

    public function get_otp_email(){
        $arr['email'] = Session::get('otp_email');
        $arr['token'] = Session::get('session.token');
        return response()->json($arr,200);
    }


    public function edit_user(Request $req){
        $validator = Validator::make($req->all(), [
            'image' => ['required', Rule::imageFile()],
            'name' =>'required',
        
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            }
            $sid = Session::get('id');
            // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/user/' .  Users::where('id', $sid)->first()->image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/user/' . $_FILES['image']['name'])) {
            $res = Users::where('id', $sid)->update([
                'image'=>$_FILES['image']['name'],
                'name'=>$req->name
            ]);
            if ($res) {
                return response()->json([
                    'message' => "User Updated successfully"
                ], 200);
            } else {
                return response()->json([
                    'message' => "Something went wrong"
                ], 302);
            }
        }
    }
}
}
