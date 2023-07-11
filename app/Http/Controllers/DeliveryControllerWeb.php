<?php

namespace App\Http\Controllers;

use App\Models\delivery_fees;
use App\Models\DeliveryFee;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class DeliveryControllerWeb extends Controller
{
    //
    public function delivery_fee_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'free_delivery' => ['required'],
            'basic_delivery_charge' => ['required'],
            'charge_per_kilo' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if(DeliveryFee::where('status','Active')->count() == 1){
                return response()->json(["error"=>["Sorry, You can only have one active delivery fee."]
                ],302 );
            }
                    $delivery_fee = new DeliveryFee();
                    $delivery_fee->free_delivery = $request->free_delivery;
                    $delivery_fee->charge_per_kilo = $request->charge_per_kilo;
                    $delivery_fee->basic_delivery_charge = $request->basic_delivery_charge;
                    $delivery_fee->status = "Active";
                    $result = $delivery_fee->save();
                    if ($result) {
                        return response()->json([
                            "message" => "Delivery Fee created successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                
                }
    }

    public function delete_delivery_fee($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of delivery_fee for deleting"
            ], 302);
        }
        $delivery_fee = DeliveryFee::where('id', $id)->update([
            'status' => 'delete',
        ]);
        if ($delivery_fee) {

            return response()->json([
                "message" => "delivery_fee deleted successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Something went wrong!"
            ], 302);
        }
    }
    public function delivery_fees($id = null)
    {
        if (!$id) {
            $delivery_fees = DeliveryFee::where('status', 'Active')->get();
            // $delivery_fees = DeliveryFee::where('status', 'Active')->count();
            $create_btn = "hide";

            if ($delivery_fees->count() == 0) {
                $create_btn = "show";
            }
            return view('admin.delivery_fees', compact('delivery_fees', 'create_btn'));
        } else {
            $delivery_fee = DeliveryFee::where('status', 'Active')->where('id', $id)->get();
            return view('admin.delivery_fees', compact('delivery_fee'));
        }
    }



    public function edit_delivery_fee(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'free_delivery' => ['required'],
            'basic_delivery_charge' => 'required',
            'charge_per_kilo' => 'required'
        ]);
           

            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
               if(DeliveryFee::where('status','Active')->count() != 1){
                return response()->json([
                    "error" => ["Sorry, You can only have one edit delivery fee."]
                ], 302);
               }
                $category = DeliveryFee::where('id', $id)->update([
                    'free_delivery' => $request->free_delivery,
                    'basic_delivery_charge' => $request->basic_delivery_charge,
                'charge_per_kilo' => $request->charge_per_kilo
                ]);
                if ($category) {
                    return response()->json([
                        "message" => "Delivery Fee updated successfully"
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "Something went wrong"
                    ], 302);
                }
                
            }
        
    }

    public function delivery_fee_create_view()
    {
        $delivery_fees = DeliveryFee::where('status', 'Active')->count();
        if($delivery_fees == 0){
            return view('admin.delivery-fee-create', compact('delivery_fees'));
        }
        
    }

    public function delivery_fee_edit($id)
    {
        $delivery_fee = DeliveryFee::where('id', $id)->where('status', 'Active');
        if ($delivery_fee->count() > 0) {
            $delivery_fee = $delivery_fee->first();
            return view('admin.delivery-fee-edit', compact('delivery_fee'));
        } else {
            return redirect('/admin/delivery_fees');
        }
    }
}
