<?php

namespace App\Http\Controllers;

use App\Models\Flames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlameController extends Controller
{
    //

    public function get_flames(){
        $uid = session()->get('id');
        $flames = Flames::where('user_id',$uid)->get();
        if($flames->count() > 0){
            return response()->json($flames,200);
        }
        else{
            return response()->json([
                'message'=>'Flames not exists'
            ],302);
        }

    }

    public function update_flame_status(Request $request,$id){
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
        if (Flames::where('status', $request->status)->where('id', $id)->count() > 0) {
            return response()->json([
                'message' => 'Flames Already updated'
            ], 302);
        }
        $flames = Flames::where('id', $id)->update(['status'=>$request->status]);
        if($flames){
            return response()->json([
               'message'=>'Flame Status Updated Successfully!'
            ],200);
        }
        else{
            return response()->json([
                'message' => 'Something Went wrong'
            ], 302);
        }
    }
}
