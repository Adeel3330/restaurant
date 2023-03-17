<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function product_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'name' => ['required', Rule::unique('products')->where('status', 'Active')],
            'image' => ['required', Rule::imageFile()],
            'price' => 'required',
            'description'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], '../public/image/sub_category/' . $_FILES['image']['name'])) {
                    $category = new Products();
                    $category->name = $request->name;
                    $category->category_id = $request->category_id;
                    $category->sub_category_id = $request->sub_category_id;
                    $category->image = $_FILES['image']['name'];
                    $category->description = $request->description;
                    $category->status = "Active";
                    if ($category->save()) {
                        return response()->json([
                            "message" => "Product created successfully"
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

    public function delete_products($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of Product for deleting"
            ], 302);
        }
        if (Products::where('id', $id)->where('status', 'Active')->count() > 0) {
            $products = Products::where('sub_category_id', $id)->update([
                    'status' => 'delete'
                ]);
            if ($products) {
                
                return response()->json([
                    "message" => "Product deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Product not found or already deleted"
            ], 302);
        }
    }
    public function products($id = null)
    {
        if (!$id) {
            $category = Products::where('status', 'Active');
            if ($category->count() > 0) {

                return response()->json(
                    $category->with('category')->with('sub_category')->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No products found"
                ], 302);
            }
        } else {
            $category = Products::with('category','sub_category')->where('status', 'Active')->where('id', $id);
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No product found"
                ], 302);
            }
        }
    }



    public function edit_product(Request $request, $id)
    {
        if (Products::where('status', 'Active')->where('id', $id)->count() > 0) {
            if ($_FILES) {
                $validator = Validator::make($request->all(), [
                    'category_id' => ['required'],
                    'sub_category_id'=>'required',
                    'name' => ['required'],
                    'image' => ['required', Rule::imageFile()],
                    'description'=>'required',
                    'price'=>'required'
                    
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'category_id' => 'required',
                    'sub_category_id' => 'required',
                    'description' => 'required',
                    'price' => 'required'
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Products::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
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
                    unlink('../public/image/category/' .  Products::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], '../public/image/sub_category/' . $_FILES['image']['name'])) {
                        $category = Products::where('id', $id)->update([
                            'name' => $request->name,
                            'category_id' => $request->category_id,
                            'sub_category_id' => $request->sub_category_id,
                            'image' => $_FILES['image']['name'],
                            'description'=>$request->description,
                            'price'=>$request->price,
                        ]);
                        if ($category) {
                            return response()->json([
                                "message" => "Product updated successfully"
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
                    $category = Products::where('id', $id)->update([
                        'name' => $request->name,
                        'category_id' => $request->category_id,
                        'sub_category_id' => $request->sub_category_id,
                        'description' => $request->description,
                        'price' => $request->price,
                    ]);
                    if ($category) {
                        return response()->json([
                            "message" => "Products updated successfully"
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
                "message" => "Product not exists"
            ], 302);
        }
    }
}
