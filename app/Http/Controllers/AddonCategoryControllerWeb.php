<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Restaurants;
// use App\Models\AddonSubCategory;
use Illuminate\Http\Request;
use App\Models\AddonCategory;
use Illuminate\Validation\Rule;
use App\Models\AddonSubCategory;
use App\Models\SubAddonCategory;
use Illuminate\Support\Facades\Validator;

class AddonCategoryControllerWeb extends Controller
{
    public function addon_category_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('addon_categories')->where('status', 'Active')],
            // 'image' => ['required', Rule::imageFile()],
            'restaurant_ids' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            // if ($_FILES['image']['size'] > 2000000) {
            //     return response()->json([
            //         "message" => "Max file size is 2mb"
            //     ], 302);
            // } else {
                // if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/addon_category/' . $_FILES['image']['name'])) {
                    foreach ($request->restaurant_ids as $restaurant_id) {
                        $addon_category = new AddonCategory();
                        $addon_category->name = $request->name;
                        $addon_category->restaurant_id = $restaurant_id;
                        // $addon_category->image = $_FILES['image']['name'];
                        $addon_category->status = "Active";
                        $result = $addon_category->save();
                    }

                    if ($result) {
                        return response()->json([
                            "message" => "Addon category created successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                // } else {
                //     return response()->json([
                //         "message" => "File not move try again!"
                //     ], 302);
                // }
            // }
        }
    }

    public function delete_addon_category($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of products for deleting"
            ], 302);
        }
        if (AddonCategory::where('id', $id)->where('status', 'Active')->count() > 0) {
            $addon_category = AddonCategory::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($addon_category) {
                // $subaddon_category = AddonSubCategory::where('addon_category_id', $id)->update([
                //     'status' => 'delete'
                // ]);
                // $products = Products::where('addon_category_id', $id)->update([
                //     'status' => 'delete'
                // ]);
                return response()->json([
                    "message" => "Addon category deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Addon_category not found or already deleted"
            ], 302);
        }
    }
    public function addon_categories($id = null)
    {
        $addon_categories = AddonCategory::with('restaurant')->where('status', 'Active')->get();
        return view('admin.addon-categories', compact('addon_categories'));
    }
    public function edit_addon_category(Request $request, $id)
    {
        if (AddonCategory::where('status', 'Active')->where('id', $id)->count() > 0) {
           $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'restaurant_id' => 'required'
                ]);
            //  dd(Addon_categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (AddonCategory::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
                return response()->json([
                    "message" => "The name has already been taken"
                ], 302);
            }
            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
                
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/addon_category/' .  Addon_categories::where('id', $id)->first()->image);
                    // if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/addon_category/' . $_FILES['image']['name'])) {
                        $addon_category = AddonCategory::where('id', $id)->update([
                            'name' => $request->name,
                            'restaurant_id' => $request->restaurant_id,
                           
                        ]);
                        if ($addon_category) {
                            return response()->json([
                                "message" => "Addon category updated successfully"
                            ], 200);
                        } else {
                            return response()->json([
                                "message" => "Something went wrong"
                            ], 302);
                        }
            }
        } else {
            return response()->json([
                "message" => "Addon_category not exists"
            ], 302);
        }
    }

    public function addon_category_create_view()
    {
        $restaurants = Restaurants::where('status', 'Active')->get();
        return view('admin.addon-category-create', compact('restaurants'));
    }

    public function edit_addon_category_view($id)
    {
        $addon_category = AddonCategory::where('id', $id);
        if ($addon_category->count() > 0) {
            $addon_category = $addon_category->first();
            $restaurants = Restaurants::where('status', 'Active')->get();
            return view('admin.addon-category-edit', compact('restaurants', 'addon_category'));
        } else {
            return redirect('/admin/addon-categories');
        }
    }
}
