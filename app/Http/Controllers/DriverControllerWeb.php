<?php

namespace App\Http\Controllers;


use App\Models\Driver;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverControllerWeb extends Controller
{
    public function driver_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', Rule::unique('drivers')->where('status', 'Active')],
            'password' => 'required',
            'phone_no' => 'required',
            'image' => ['required', Rule::imageFile()],
            'restaurant_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/driver/' . $_FILES['image']['name'])) {
                    $driver = new Driver();
                    $driver->email = $request->email;
                    $driver->password = Hash::make($request->password);
                    $driver->phone_no = $request->phone_no;
                    $driver->name = $request->name;
                    $driver->image = $_FILES['image']['name'];
                    $driver->restaurant_id = $request->restaurant_id;
                    $driver->status = "Active";
                    if ($driver->save()) {
                        return response()->json([
                            "message" => "Driver created successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                } else {
                    return response()->json([
                        "message" => "File not move try again!"
                    ], 302);
                }
            }
        }
    }

    public function delete_driver($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of driver for deleting"
            ], 302);
        }
        if (Driver::where('id', $id)->where('status','!=' ,'delete')->count() > 0) {
            $driver = Driver::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($driver) {
                return response()->json([
                    "message" => "Driver deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Driver not found or already deleted"
            ], 302);
        }
    }


    public function update_driver_status($id = null,Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        if (Driver::where('id', $id)->where('status', 'Pending')->count() > 0) {
            $driver = Driver::where('id', $id)->update([
                'status' => $request->status,
            ]);
            if ($driver) {
                return response()->json([
                    "message" => "Driver ".$request->status." successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Driver not found or already deleted"
            ], 302);
        }
    }



    public function drivers($id = null)
    {
        $drivers = Driver::where('status','!=', 'delete')->with('restaurant')->get();
        return view('admin.drivers', compact('drivers'));
    }



    public function edit_driver(Request $request, $id)
    {
        if (Driver::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'phone_no' => 'required',
                    'image' => ['required', Rule::imageFile()],
                    'restaurant_id' => ['required'],
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'phone_no' => 'required',
                    'restaurant_id' => ['required'],
                    // 'image' => ['required', Rule::imageFile()],
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Driver::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->where('restaurant_id','LIKE',$request->restaurant_id)->count() > 0) {
                return response()->json([
                    "message" => "The name has already been taken"
                ], 302);
            }
            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
                if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                    if ($_FILES['image']['size'] > 2000000) {
                        return response()->json([
                            "message" => "Max file size is 2mb"
                        ], 302);
                    }
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/driver/' .  Categories::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/driver/' . $_FILES['image']['name'])) {
                        $driver = Driver::where('id', $id)->update([
                            'name' => $request->name,
                            'phone_no' => $request->phone_no,
                            'restaurant_id' => $request->restaurant_id,
                            'image' => $_FILES['image']['name']
                        ]);
                        if ($driver) {
                            return response()->json([
                                "message" => "Driver updated successfully"
                            ], 200);
                        } else {
                            return response()->json([
                                "message" => "Something went wrong"
                            ], 302);
                        }
                    } else {
                        return response()->json([
                            "message" => "File not move try again!"
                        ], 302);
                    }
                } else {
                    $driver = Driver::where('id', $id)->update([
                        'name' => $request->name,
                        'phone_no' => $request->phone_no,
                        'restaurant_id' => $request->restaurant_id,
                    ]);
                    if ($driver) {
                        return response()->json([
                            "message" => "Driver updated successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                }
            }
        } else {
            return response()->json([
                "message" => "driver not exists"
            ], 302);
        }
    }

    public function driver_create_view()
    {
        $restaurants = Restaurants::where('status','Active')->get();
        return view('admin.driver-create',compact('restaurants'));
    }

    public function edit_driver_view($id)
    {
        $driver = Driver::where('id', $id);
        if ($driver->count() > 0) {
            $driver = $driver->first();
            $restaurants = Restaurants::where('status', 'Active')->get();
            // return view('admin.driver-create', compact('restaurants'));
            return view('admin.driver-edit', compact('driver', 'restaurants'));
        } else {
            return redirect('/admin/drivers');
        }
    }
}
