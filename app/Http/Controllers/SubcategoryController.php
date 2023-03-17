<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
    //
    //
    public function sub_category_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' =>'required',
            'name' => ['required', Rule::unique('sub_categories')->where('status', 'Active')],
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
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/image/sub_category/' . $_FILES['image']['name'])) {
                    $category = new SubCategories();
                    $category->name = $request->name;
                    $category->name = $request->name;
                    $category->restaurant_id = $request->restaurant_id;
                    $category->image = $_FILES['image']['name'];
                    $category->status = "Active";
                    if ($category->save()) {
                        return response()->json([
                            "message" => "Sub Category created successfully"
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

    public function delete_sub_category($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of Sub Category for deleting"
            ], 302);
        }
        if (SubCategories::where('id', $id)->where('status', 'Active')->count() > 0) {
            $category = SubCategories::where('id', $id)->update([
                'status' => 'delete',
            ]);
            if ($category) {
                // $products = Products::where('sub_category_id', $id)->update([
                //     'status' => 'delete'
                // ]);
                return response()->json([
                    "message" => "Sub Category deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Sub Category not found or already deleted"
            ], 302);
        }
    }
    public function sub_categories($id = null)
    {
        if (!$id) {
            $category = SubCategories::with('category','restaurant')->where('status', 'Active');
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No sub category found"
                ], 302);
            }
        } else {
            $category = SubCategories::with('category','restaurant')->where('status', 'Active')->where('id', $id);
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No sub category found"
                ], 302);
            }
        }
    }



    public function edit_sub_category(Request $request, $id)
    {
        if (SubCategories::where('status', 'Active')->where('id', $id)->count() > 0) {
            if ($_FILES) {
                $validator = Validator::make($request->all(), [
                    'category_id' => ['required'],
                    'name' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                    'restaurant_id'=>'required'
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'category_id'=>'required',
                    'restaurant_id'=>'required'
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (SubCategories::where('status', 'Active')->where('id', '!=', $id)->where('name','LIKE',$request->name)->count() > 0) {
                return response()->json([
                    "message" => "The name has already been taken"
                ], 302);
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
                    unlink($_SERVER['DOCUMENT_ROOT'].'/image/category/' .  SubCategories::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/image/sub_category/' . $_FILES['image']['name'])) {
                        $category = SubCategories::where('id', $id)->update([
                            'name' => $request->name,
                            'restaurant_id'=>$request->restaurant_id,
                            'category_id'=>$request->category_id,
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
                    $category = SubCategories::where('id', $id)->update([
                        'name' => $request->name,
                        'restaurant_id'=>$request->restaurant_id,
                        'category_id'=>$request->category_id
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
                }
            }
        } else {
            return response()->json([
                "message" => "Sub Category not exists"
            ], 302);
        }
    }
}
