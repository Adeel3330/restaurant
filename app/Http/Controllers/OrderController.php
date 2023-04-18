<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\AddtoCarts;
use App\Models\OrderItems;
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
            'transaction_id' => 'required',
            'items.*.product_id' => 'required',
            'items.*.payment' => 'required',
            'items.*.quantity' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        // dd($request);
        $sid = $request->session()->get('id');
        $carts = Orders::where('status', 'pending')->where('user_id', $sid)->where('transaction_id',$request->transaction_id);
        if ($carts->count() > 0) {
           $order = $carts->first();
           $order_id = $order['id'];
            foreach ($request->items as $item) {
                $count = OrderItems::where('order_id', $order_id)->where('product_id',$item['product_id'])->count() ;
                if($count > 0){
                    $order_items = OrderItems::where('order_id', $order_id)->where('product_id', $item['product_id'])->update([
                        'payment' => $item['payment'],
                        'quantity' => $item['quantity'],
                    ]);   
                }
                else
                {
                    $order_items = OrderItems::create([
                        'product_id' => $item['product_id'],
                        'payment' => $item['payment'],
                        'quantity' => $item['quantity'],
                        'order_id' => $order_id,
                    ]);
                }
            }
            if($order_items){
                return response()->json([
                    "message" => "Order Updated successfully",
                ], 200);
            }
            else
            {
                return response()->json([
                    "message" => "Something went wrong"
                ], 302); 
            }
            

        }
      
        $order_no = Str::random(8);
        $orderscreate = new Orders;
        $orderscreate->user_id = $sid;
        $orderscreate->status = "pending";
        $orderscreate->transaction_id = $request->transaction_id;
        $orderscreate->order_no = $order_no;
        $orderscreate->save();
        foreach($request->items as $key=>$item){
            $order = Orders::where('user_id',$sid)->where('status','pending')->orderBy('created_at','desc')->first();
            $order_id = $order['id'];
            $order_items = new OrderItems;
            $order_items->order_id = $order_id;
            $order_items->product_id = $item['product_id'];
            $order_items->payment = $item['payment'];
            $order_items->quantity = $item['quantity'];
            $res = $order_items->save();
        }
        if($res){
            $carts = AddtoCarts::where('user_id',$sid)->update([
                'status'=>'delete',
            ],200);
            return response()->json([
                "message" => "Product order successfully",
                "order_no" => $order_no
            ], 200);
        }
        else
        {
            return response()->json([
                "message" => "Something went wrong"
            ], 302);  
        }
        // exit;
       
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
                // $order->with('order_items')->get();
                // dd($order);
                return response()->json($order->with('user')->get(), 200);
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
                return response()->json($order->with('user', 'product')->first(), 200);
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
                return response()->json($order->with('user', 'product')->get(), 200);
            } else {
                return response()->json([
                    "message" => "No cart found"
                ], 302);
            }
        } else {
            $order = AddtoCarts::where('status', '!=', 'delete')->where('id', $id);
            if ($order->count() > 0) {
                return response()->json($order->with('user', 'product')->first(), 200);
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
                return response()->json($order->with('user', 'product')->get(), 200);
            } else {
                return response()->json([
                    "message" => "No cart found"
                ], 302);
            }
        } else {
            $order = AddtoCarts::where('status', '!=', 'delete')->where('id', $id)->where('user_id', $sid);
            if ($order->count() > 0) {
                return response()->json($order->with('user', 'product')->first(), 200);
            } else {
                return response()->json([
                    "message" => "No cart found"
                ], 302);
            }
        }
    }


    public function order_get($id = null)
    {

        $sid = Session::get('id');
        if (!$id) {
            $order = Orders::where('status', '!=', 'delete')->where('user_id', $sid);
            if ($order->count() > 0) {
                $order_items = OrderItems::with('product')->get();
                $orders = $order->with('user')->get();
                foreach($orders as $order){
                    $order['orders_items'] = $order_items;
                }
                return response()->json($orders, 200);
            } else {
                return response()->json([
                    "message" => "No Order found"
                ], 302);
            }
        } else {
            $order = Orders::where('status', '!=', 'delete')->where('id', $id)->where('user_id', $sid);
            if ($order->count() > 0) {
                $order_items = OrderItems::with('product')->get();
                $orders = $order->with('user')->get();
                foreach ($orders as $order) {
                    $order['orders_items'] = $order_items;
                }
                return response()->json($orders, 200);
                return response()->json($order->with('user', 'product')->first(), 200);
            } else {
                return response()->json([
                    "message" => "No Order found"
                ], 302);
            }
        }
    }


    public function order_update_status($id){
        if(!$id){
            return response()->json([
                "message"=>"Please Enter Id of Order for update",
            ],302);
        }
        $order = Orders::where('id',$id)->update([
            'status'=> 'completed',
        ]);
        if($order){
            return response()->json([
                "message" => "Order Completed successfully",
            ], 200);
        }
        else{
            return response()->json([
                "message" => "Something Went wrong",
            ], 200);
        }
    }

}
