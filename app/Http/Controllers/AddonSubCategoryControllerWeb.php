<?php

namespace App\Http\Controllers;


use App\Models\AddonCategory;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Models\AddonSubCategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class AddonSubCategoryControllerWeb extends Controller
{
    public function addon_sub_category_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => ['required', Rule::unique('addon_sub_categories')->where('status', 'Active')],
            'image' => ['required', Rule::imageFile()],
            'restaurant_ids' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/addon_sub_category/' . $_FILES['image']['name'])) {

                    foreach ($request->restaurant_ids as $restaurant_id) {
                        $category = new AddonSubCategory();
                        $category->addon_category_id = $request->category_id;
                        $category->name = $request->name;
                        $category->restaurant_id = $restaurant_id;
                        $category->image = $_FILES['image']['name'];
                        $category->status = "Active";
                        $result = $category->save();
                    }
                    if ($result) {
                        return response()->json([
                            "message" => "Addon Sub Category created successfully"
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

    public function delete_addon_sub_category($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of Sub Category for deleting"
            ], 302);
        }
        if (AddonSubCategory::where('id', $id)->where('status', 'Active')->count() > 0) {
            $category = AddonSubCategory::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($category) {
                // $products = Products::where('addon_sub_category_id', $id)->update([
                //     'status' => 'delete'
                // ]);
                return response()->json([
                    "message" => "Addon Sub Category deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Addon Sub Category not found or already deleted"
            ], 302);
        }
    }
    public function addon_sub_categories($id = null)
    {
        $addon_sub_categories = AddonSubCategory::with('addon_category', 'restaurant')->where('status', 'Active')->get();
        // dd($addon_sub_categories);
        return view('admin.addon-sub-categories', compact('addon_sub_categories'));
    }
    public function edit_addon_sub_category(Request $request, $id)
    {
        if (AddonSubCategory::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    'category_id' => ['required'],
                    'name' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                    'restaurant_id' => 'required'
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'category_id' => 'required',
                    'restaurant_id' => 'required'
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (AddonSubCategory::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
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
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/addon_sub_category/' .  SubCategories::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/addon_sub_category/' . $_FILES['image']['name'])) {
                        $category = AddonSubCategory::where('id', $id)->update([
                            'name' => $request->name,
                            'restaurant_id' => $request->restaurant_id,
                            'addon_category_id' => $request->category_id,
                            'image' => $_FILES['image']['name']
                        ]);
                        if ($category) {
                            return response()->json([
                                "message" => "Sub Category updated successfully"
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
                    $category = AddonSubCategory::where('id', $id)->update([
                        'name' => $request->name,
                        'restaurant_id' => $request->restaurant_id,
                        'addon_category_id' => $request->category_id
                    ]);
                    if ($category) {
                        return response()->json([
                            "message" => "Addon Sub Category updated successfully"
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
                "message" => "Sub Category not exists"
            ], 302);
        }
    }

    public function addon_sub_category_create_view()
    {
        $categories = AddonCategory::where('status', 'Active')->get();
        $restaurants = Restaurants::where('status', 'Active')->get();
        // dd($categories);
        return view('admin.addon-sub-category-create', compact('categories', 'restaurants'));
    }
    public function  edit_addon_sub_category_view($id)
    {
        $addon_sub_category = AddonSubCategory::where('status', 'Active')->where('id', $id);
        if ($addon_sub_category->count() <= 0) return redirect('/admin/sub_categories');
        $addon_sub_category = $addon_sub_category->first();
        $categories = AddonCategory::where('status', 'Active')->get();
        $restaurants = Restaurants::where('status', 'Active')->get();
        return view('admin.addon-sub-category-edit', compact('categories', 'restaurants', 'addon_sub_category'));
    }
}
