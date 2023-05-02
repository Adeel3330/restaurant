<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\SubCategories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminControllerWeb extends Controller
{
    public function admin_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', Rule::unique('admins')->where('status', 'Active')],
            'password' => 'required',
            'phone_no' => 'required',
            'image'=>['required',Rule::imageFile()],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/admin/' . $_FILES['image']['name'])) {
                    $admin = new Admins();
                    $admin->email = $request->email;
                    $admin->password = Hash::make($request->password);
                    $admin->phone_no = $request->phone_no;
                    $admin->name = $request->name;
                    $admin->image = $_FILES['image']['name'];
                    $admin->status = "Active";
                    if ($admin->save()) {
                        return response()->json([
                            "message" => "Admin created successfully"
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

    public function delete_admin($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of admin for deleting"
            ], 302);
        }
        if (Admins::where('id', $id)->where('status', 'Active')->count() > 0) {
            $admin = Admins::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($admin) {
                return response()->json([
                    "message" => "admin deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "admin not found or already deleted"
            ], 302);
        }
    }
    public function admins($id = null)
    {
        $admins = Admins::where('status', 'Active')->get();
        return view('admin.admins', compact('admins'));
    }



    public function edit_admin(Request $request, $id)
    {
        if (Admins::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'email' => ['required'],
                    'password' => 'required',
                    'phone_no' => 'required',
                    'image' => ['required', Rule::imageFile()],
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'email' => ['required'],
                    'password' => 'required',
                    'phone_no' => 'required',
                    // 'image' => ['required', Rule::imageFile()],
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Admins::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
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
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/admin/' .  Categories::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/admin/' . $_FILES['image']['name'])) {
                        $admin = Admins::where('id', $id)->update([
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'phone_no' => $request->phone_no,
                            'image' => $_FILES['image']['name']
                        ]);
                        if ($admin) {
                            return response()->json([
                                "message" => "Admin updated successfully"
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
                    $admin = Admins::where('id', $id)->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'phone_no' => $request->phone_no
                    ]);
                    if ($admin) {
                        return response()->json([
                            "message" => "Admin updated successfully"
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
                "message" => "admin not exists"
            ], 302);
        }
    }

    public function admin_create_view()
    {
        return view('admin.admin-create');
    }

    public function edit_admin_view($id)
    {
        $admin = Admins::where('id', $id);
        if ($admin->count() > 0) {
            $admin = $admin->first();
            return view('admin.admin-edit', compact('admin'));
        } else {
            return redirect('/admin/admins');
        }
    }
}
