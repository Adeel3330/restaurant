<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersAdditionals;
use App\Models\Users_Additionals;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Validator;

class Homecontroller extends Controller
{
    //
    public function location_insert(Request $request){
        $validator = Validator::make($request->all(), [
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            $sid = $request->session()->get('id');
            if(UsersAdditionals::where('user_id',$sid)->where('status','Active')->count() > 0){
                $user_update = UsersAdditionals::where('user_id', $sid)->where('status', 'Active')->update([
                    'longitude'=>$request->longitude,
                    'latitude'=>$request->latitude,
                ]);
                if($user_update){
                    return response()->json([
                        "message"=>"Location updated successfully",
                    ],200);

                }
                else
                {
                    return response()->json([
                        "message" => "Something went wrong",
                    ], 302);
                }
            }
            else
            {
                $location = new UsersAdditionals();
                $location->user_id = $sid;
                $location->longitude = $request->longitude;
                $location->latitude = $request->latitude;
                $location->status = "Active";
                if ($location->save()) {
                    return response()->json([
                        "message" => "Location inserted successfully",
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "Something went wrong",
                    ], 302);
                }
            }
        }
    }

    public function get_session(Request $req){
        $session = $req->session()->all();
        return response()->json([
            "session"=> $session
        ],200);
    }
    
}
