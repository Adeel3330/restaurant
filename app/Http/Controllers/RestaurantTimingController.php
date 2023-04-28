<?php

namespace App\Http\Controllers;

use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\RestaurantsTimings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RestaurantTimingController extends Controller
{
    public function week_days(){
        $weeks = RestaurantsTimings::all();
        return view('admin.weeks',compact('weeks'));
    }
    public function edit_week_day($id){
        if(!$id){
            return redirect('/admin/weeks');
        }
        $week = RestaurantsTimings::where('id',$id);
        if($week->count() <= 0){
            return response()->json(['message'=>'Enter Valid Id'],302);
        }
        $week = $week->first();
        return view('/admin/week-edit',compact('week'));
    }

    public function week_day_create(Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        if(RestaurantsTimings::where('name','LIKE','%'.$req->name.'%')->count() > 0){
            return response()->json([
                'message'=>'Week name already exists'
            ],302);
        }
        $restaurant  = new RestaurantsTimings();
        $restaurant->name = $req->name;
        $restaurant->opening_time = $req->opening_time;
        $restaurant->closing_time = $req->closing_time;
        if($restaurant->save()){
            return response()->json([
                'message'=>'Weeks Created successfully'
            ],200);
        }
        else
        {
            return response()->json([
                'message' => 'Somethoing Went Wrong'
            ], 302);
        }
    }
    public function week_day_edit($id,Request $req)
    {
        # code...
        if(!$id){
            return redirect('/admin/week_days');
        }

        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        // dd(RestaurantsTimings::where('name', 'LIKE', '%' . $req->name . '%')->where('id', '!=', $id)->count());
        if (RestaurantsTimings::where('name', 'LIKE', '%' . $req->name . '%')->where('id','!=',$id)->count() > 0) {
            return response()->json([
                'message' => 'Week name already exists'
            ], 302);
        }
        $restaurant  = RestaurantsTimings::where('id',$id)->update([
            'name'=>$req->name,
            'opening_time'=>$req->opening_time,
            'closing_time' => $req->closing_time,
        ]);
        if($restaurant) {
            return response()->json([
                'message' => 'Weeks Update successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something Went Wrong'
            ], 302);
        }


    }

    public function delete_week_day($id)
    {
        # code...
        if(!$id){
            return response()->json([
                'message'=>'Week Id invalid or Already deleted'
            ],302);
        }

        $restaurant = RestaurantsTimings::where('id',$id)->delete();
        if($restaurant){
            return response()->json([
                'message'=>'Week Deleted successfully'
            ],200);
        }
        else
        {
            return response()->json([
                'message' => 'Something went wrong'
            ], 302);
        }
    }
}
