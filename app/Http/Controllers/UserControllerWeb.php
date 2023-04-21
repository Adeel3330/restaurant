<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Admins;
use App\Models\Orders;
use App\Models\Products;
use App\Models\Categories;
use App\Models\OrderItems;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserControllerWeb extends Controller
{
    //
    function getusers($id = null)
    {
        if (!isset($id) && empty($id)) {
            $users = new Users;
            $get = users::where('status', 'Active');
            $usersresults = $get->get();
            return view('/admin/users',compact('userresults'));
           
        } else {
            // return $id;
            $users = new users;
            $get = users::where('id', $id)->where('status', 'Active');
            $usersresults = $get->get();
            return view('/admin/users', compact('userresults'));
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

    public function logout(){
        $ses = Session::flush();
        return redirect('/admin/login');
        
    }

    public function AdminLogin(Request $request)
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

    public function dashboard(){
        $data = [];
        $data['restaurants'] = Restaurants::where('status','Active')->count();
        $data['users'] = Users::where('status', 'Active')->count();
        $data['products'] = Products::where('status', 'Active')->count();
        $data['orders_count'] = Orders::where('status','!=','delete')->count();
        $orders = Orders::where('status', '!=', 'delete')->with('user')->get();
        foreach ($orders as $order) {
            $order_items = OrderItems::where('order_id', $order['id'])->with('product')->get();
            $order['orders_items'] = $order_items;
        }

        return view('admin.index',compact('data','orders'));
    }
}
