<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Categories;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Models\SubCategories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CategoryControllerWeb extends Controller
{
    //
    public function category_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique('categories')->where('status', 'Active')],
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
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/category/' . $_FILES['image']['name'])) {
                    foreach($request->restaurant_ids as $restaurant_id){
                        $category = new Categories();
                        $category->name = $request->name;
                        $category->restaurant_id = $restaurant_id;
                        $category->image = $_FILES['image']['name'];
                        $category->status = "Active";
                        $result = $category->save();
                    }
                    
                    if ($result) {
                        return response()->json([
                            "message" => "Category created successfully"
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

    public function delete_category($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of products for deleting"
            ], 302);
        }
        if (Categories::with('sub_category', 'product')->where('id', $id)->where('status', 'Active')->count() > 0) {
            $category = Categories::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($category) {
                $subcategory = SubCategories::where('category_id', $id)->update([
                    'status' => 'delete'
                ]);
                $products = Products::where('category_id', $id)->update([
                    'status' => 'delete'
                ]);
                return response()->json([
                    "message" => "Category deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Category not found or already deleted"
            ], 302);
        }
    }
    public function categories($id = null)
    {
            $categories = Categories::with('restaurant')->where('status', 'Active')->get();
            return view('admin.categories',compact('categories'));
    }



    public function edit_category(Request $request, $id)
    {
        if (Categories::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                    'restaurant_id' => 'required'
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'restaurant_id' => 'required'
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Categories::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
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
                    // unlink($_SERVER['DOCUMENT_ROOT'] . '/image/category/' .  Categories::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/category/' . $_FILES['image']['name'])) {
                        $category = Categories::where('id', $id)->update([
                            'name' => $request->name,
                            'restaurant_id' => $request->restaurant_id,
                            'image' => $_FILES['image']['name']
                        ]);
                        if ($category) {
                            return response()->json([
                                "message" => "Category updated successfully"
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
                    $category = Categories::where('id', $id)->update([
                        'name' => $request->name,
                        'restaurant_id' => $request->restaurant_id
                    ]);
                    if ($category) {
                        return response()->json([
                            "message" => "Category updated successfully"
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
                "message" => "Category not exists"
            ], 302);
        }
    }

    public function category_create_view(){
        $restaurants = Restaurants::where('status','Active')->get();
        return view('admin.category-create',compact('restaurants'));
    }

    public function edit_category_view($id){
        $category = Categories::where('id',$id);
        if($category->count() > 0){
            $category = $category->first();
            $restaurants = Restaurants::where('status','Active')->get();
            return view('admin.category-edit',compact('restaurants','category'));
        }
        else
        {
            return redirect('/admin/categories');
        }
    }
}
