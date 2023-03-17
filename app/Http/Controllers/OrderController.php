<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\AddtoCarts;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //
    public function add_to_cart(Request $request){
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        $sid = $request->session()->get('id');
        $carts = AddtoCarts::where('status','Active')->where('user_id',$sid)->where('product_id',$request->product_id);
        if($carts->count() > 0){
            $carts_update = $carts->update([
                'quantity'=>$request->quantity
            ]);
            if ($carts_update) {
                return response()->json([
                    "message" => "Product add to cart updated successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong"
                ], 302);
            }
        }
        else
        {
            $carts = new AddtoCarts();
            $carts->user_id = $sid;
            $carts->product_id = $request->product_id;
            $carts->quantity = $request->quantity;
            $carts->status = "Active";
            if($carts->save()){
                return response()->json([
                    "message"=>"Product add to cart successfully"
                ], 200);
            }
            else
            {
                return response()->json([
                    "message" =>"Something went wrong"
                ], 302);
            }
        }
    }
    
    public function order(Request $request){
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'transaction_id' =>'required',
            'payment'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        $sid = $request->session()->get('id');
        $carts = Orders::where('status', 'pending')->where('user_id', $sid)->where('product_id', $request->product_id);
        if ($carts->count() > 0) {
                return response()->json([
                    "message" => "Order already exists"
                ], 302);
        } else {
            $order_no = Str::random(8);
            $orders = new Orders();
            $orders->user_id = $sid;
            $orders->product_id = $request->product_id;
            $orders->quantity = $request->quantity;
            $orders->status = "pending";
            $orders->order_no = $order_no;
            $orders->payment = $request->payment;
            $orders->transaction_id = $request->transaction_id;
            if ($orders->save()) {
                return response()->json([
                    "message" => "Product order successfully",
                    "order_no" => $order_no
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong"
                ], 302);
            }
        }
    }


    public function delete_add_to_cart($id){
        $carts = AddtoCarts::where('status','Active')->where('id',$id);
        if($carts->count() > 0){
            $carts_update = $carts->update([
                'status' =>'delete'
            ]);
            if($carts_update){
                return response()->json([
                    "message" => "Cart deleted successfully"
                ], 200);
            }
            else
            {
                return response()->json([
                    "message" => "Something went wrong"
                ], 302);
            }
        }
        else{
            return response()->json([
                "message"=>"Cart already delete or unknown id"
            ], 302);
        }
    }

    public function orders($id = null){
        if(!$id){
            $order = Orders::where('status', '!=', 'delete');
            if($order->count() > 0){
                return response()->json($order->with('user','product')->get(), 302);
            }
            else
            {
                return response()->json([
                    "message" => "No order found"
                ], 302);
            }
        }
        else
        {
            $order = Orders::where('status', '!=','delete')->where('id',$id);
            if ($order->count() > 0) {
                return response()->json($order->with('user', 'product')->first(), 302);
            } else {
                return response()->json([
                    "message" => "No order found"
                ], 302);
            }
        }
    }



    public function carts($id = null)
    {
        if (!$id) {
            $order = AddtoCarts::where('status', '!=', 'delete');
            if ($order->count() > 0) {
                return response()->json($order->with('user', 'product')->get(), 302);
            } else {
                return response()->json([
                    "message" => "No cart found"
                ], 302);
            }
        } else {
            $order = AddtoCarts::where('status', '!=', 'delete')->where('id', $id);
            if ($order->count() > 0) {
                return response()->json($order->with('user', 'product')->first(), 302);
            } else {
                return response()->json([
                    "message" => "No cart found"
                ], 302);
            }
        }
    }


    public function cart($id = null)
    {
        $sid = Session::get('id');
        if (!$id) {
            $order = AddtoCarts::where('status', '!=', 'delete')->where('user_id',$sid);
            if ($order->count() > 0) {
                return response()->json($order->with('user', 'product')->get(), 302);
            } else {
                return response()->json([
                    "message" => "No cart found"
                ], 302);
            }
        } else {
            $order = AddtoCarts::where('status', '!=', 'delete')->where('id', $id)->where('user_id', $sid);
            if ($order->count() > 0) {
                return response()->json($order->with('user', 'product')->first(), 302);
            } else {
                return response()->json([
                    "message" => "No cart found"
                ], 302);
            }
        }
    }


}
