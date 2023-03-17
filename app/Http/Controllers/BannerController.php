<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    //
    public function banner_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/image/banner/' . $_FILES['image']['name'])) {
                    $banner = new Banners();
                    $banner->restaurant_id = $request->restaurant_id;
                    $banner->image = $_FILES['image']['name'];
                    $banner->status = "Active";
                    if ($banner->save()) {
                        return response()->json([
                            "message" => "Banner created successfully"
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

    public function delete_banner($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of Banner for deleting"
            ], 302);
        }
         $banner = Banners::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($banner) {
               
                return response()->json([
                    "message" => "Banner deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        
    }
    public function banners($id = null)
    {
        if (!$id) {
            $banner = Banners::with('restaurant')->where('status', 'Active');
            if ($banner->count() > 0) {

                return response()->json(
                    $banner->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No Banner found"
                ], 302);
            }
        } else {
            $banner = Banners::with('restaurant')->where('status', 'Active')->where('id', $id);
            if ($banner->count() > 0) {

                return response()->json(
                    $banner->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No banner found"
                ], 302);
            }
        }
    }



    public function edit_banner(Request $request, $id)
    {
        if (Banners::where('status', 'Active')->where('id', $id)->count() > 0) {
            if ($_FILES) {
                $validator = Validator::make($request->all(), [
                    'restaurant_id' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'restaurant_id' => ['required'],
                ]);
            }
           
            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
                if ($_FILES) {
                    if ($_FILES['image']['size'] > 2000000) {
                        return response()->json([
                            "message" => "Max file size is 2mb"
                        ], 302);
                    }
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/image/banner/' .  Banners::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/banner/' . $_FILES['image']['name'])) {
                        $category = Banners::where('id', $id)->update([
                            'restaurant_id' => $request->restaurant_id,
                            'image' => $_FILES['image']['name']
                        ]);
                        if ($category) {
                            return response()->json([
                                "message" => "Banners updated successfully"
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
                    $category = Banners::where('id', $id)->update([
                        'restaurant_id' => $request->restaurant_id
                    ]);
                    if ($category) {
                        return response()->json([
                            "message" => "Banner updated successfully"
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
                "message" => "Banner not exists"
            ], 302);
        }
    }
}
