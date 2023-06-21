<?php

namespace App\Http\Controllers;

use App\Models\RestaurantOwner;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RestaurantOwnerControllerWeb extends Controller
{
    //
    public function owner_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => ['required'],
            'email' => ['required', Rule::unique('restaurant_owners')],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
                    $owner = new RestaurantOwner();
                    $owner->email = $request->email;
                    $owner->password = Hash::make($request->password);
                    $owner->restaurant_id = $request->restaurant_id;
                    $owner->status = "Active";
                    if ($owner->save()) {
                        return response()->json([
                            "message" => "Owner created successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
              
            
        }
    }

    public function delete_owner($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of Owner for deleting"
            ], 302);
        }
        if (RestaurantOwner::where('id', $id)->where('status', '!=', 'delete')->count() > 0) {
            $owner = RestaurantOwner::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($owner) {
                return response()->json([
                    "message" => "Owner deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "owner not found or already deleted"
            ], 302);
        }
    }


    public function update_owner_status($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        if (RestaurantOwner::where('id', $id)->where('status', 'Pending')->count() > 0) {
            $owner = RestaurantOwner::where('id', $id)->update([
                'status' => $request->status,
            ]);
            if ($owner) {
                return response()->json([
                    "message" => "Owner " . $request->status . " successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "owner not found or already deleted"
            ], 302);
        }
    }



    public function owners($id = null)
    {
        $owners = RestaurantOwner::where('status', '!=', 'delete')->with('restaurant')->get();
        return view('admin.owners', compact('owners'));
    }



    public function edit_owner(Request $request, $id)
    {
        if (RestaurantOwner::where('status', 'Active')->where('id', $id)->count() > 0) {
                $validator = Validator::make($request->all(), [
                    'restaurant_id' => ['required'],
                ]);
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
          
            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
              
                    $owner = RestaurantOwner::where('id', $id)->update([
                        'restaurant_id' => $request->restaurant_id,
                    ]);
                    if ($owner) {
                        return response()->json([
                            "message" => "Owner updated successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                }
            }
       
    }

    public function owner_create_view()
    {
        $restaurants = Restaurants::where('status', 'Active')->get();
        return view('admin.owner-create', compact('restaurants'));
    }

    public function edit_owner_view($id)
    {
        $owner = RestaurantOwner::where('id', $id);
        if ($owner->count() > 0) {
            $owner = $owner->first();
            $restaurants = Restaurants::where('status', 'Active')->get();
            // return view('admin.owner-create', compact('restaurants'));
            return view('admin.owner-edit', compact('owner', 'restaurants'));
        } else {
            return redirect('/admin/owners');
        }
    }
}
