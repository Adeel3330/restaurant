<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use App\Models\Categories;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class BannerControllerWeb extends Controller
{
    public function banner_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => ['required', Rule::imageFile()],
            // 'restaurant_id' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/banner/' . $_FILES['image']['name'])) {
                    // foreach ($request->restaurant_ids as $restaurant_id) {
                        $banner = new Banners();
                        // $banner->restaurant_id = $request->restaurant_id;
                        $banner->category_id = $request->category_id;
                        $banner->image = $_FILES['image']['name'];
                        $banner->status = "Active";
                        $result = $banner->save();
                    // }
                    if ($result) {
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
            $banners = Banners::with('category')->where('status', 'Active')->get();
            return view('admin.banners',compact('banners'));
            
        } else {
            $banner = Banners::with('category')->where('status', 'Active')->where('id', $id)->get();
            return view('admin.banners', compact('banner'));
           
        }
    }



    public function edit_banner(Request $request, $id)
    {
        if (Banners::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    // 'restaurant_id' => ['required'],
                    'category_id' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    // 'restaurant_id' => ['required'],
                    'category_id' => ['required'],
                ]);
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
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/banner/' .  Banners::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/banner/' . $_FILES['image']['name'])) {
                        $category = Banners::where('id', $id)->update([
                            // 'restaurant_id' => $request->restaurant_id,
                            'category_id' => $request->category_id,
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
                        // 'restaurant_id' => $request->restaurant_id,
                        'category_id' => $request->category_id,
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

    public function banner_create_view(){
        $categories = Categories::where('status','Active')->get();
        return view('admin.banner-create',compact('restaurants', 'categories'));
    }

    public function banner_edit($id)
    {
        $banner = Banners::where('id',$id)->where('status','Active');
        if($banner->count() > 0){
            $banner = $banner->first();
            $categories = Categories::where('status','Active')->get();
            // $banner['restaurants'] = $restaurants;
            return view('admin.banner-edit',compact('banner','categories'));
        }
        else
        {
            return redirect('/admin/banners');
        }
    }
    public function get_categories_with_id($id){
        $html = '';
        $categories = Categories::where('restaurant_id',$id);
        if($categories->count() > 0){
            $categories = $categories->get();
            foreach($categories as $category){
                $html .="<option value=".$category->id.">".$category->name."</option>";
            } 
            
        }
        else{
            $html .= "<option value=''>No Category Found</option>";
        }
        return $html;

    }
}
