<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\FlavourProducts;
use App\Models\ProductFlavours;
use App\Models\Products;
use App\Models\Restaurants;
use App\Models\SubCategories;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProductControllerWeb extends Controller
{
    public function product_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            
            // 'restaurant_ids' => 'required',
            'name' => ['required', Rule::unique('products')->where('status', 'Active')],
            'image' => ['required', Rule::imageFile()],
            'price' => 'required',
            'description' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/product/' . $_FILES['image']['name'])) {
                    // foreach ($request->restaurant_ids as $restaurant_id) {
                    
                    $category = new Products();
                    $category->name = $request->name;
                    $category->category_id = $request->category_id;
                    // $category->restaurant_id = $restaurant_id;
                
                    $category->image = $_FILES['image']['name'];
                    $category->description = $request->description;
                    $category->price = $request->price;
                    $category->status = "Active";
                     $result =    $category->save();
                    // $product_id = Products::where('status', 'Active')->orderBy('id', 'desc')->first()['id'];
                    //     foreach ($request->flavour_ids as $flavour) {
                    //         FlavourProducts::create([
                    //             'product_id' => $product_id,
                    //             'flavour_id' => $flavour,
                    //         ]);
                    //     }
                    // }
                    if ($result) {
                        
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

    public function delete_product($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of Product for deleting"
            ], 302);
        }
        if (Products::where('id', $id)->where('status', 'Active')->count() > 0) {
            $products = Products::where('id', $id)->update([
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
        $products = Products::where('status', 'Active')->get();
        return view('admin.products',compact('products'));
            
    }



    public function edit_product(Request $request, $id)
    {
        if (Products::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    'category_id' => ['required'],
                    'name' => ['required'],
                    // 'restaurant_id' => 'required',
                    'image' => ['required', Rule::imageFile()],
                    'description' => 'required',
                    'price' => 'required',
                    // 'flavour_ids'=>'required',

                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'category_id' => 'required',
                   
                    'description' => 'required',
                    'price' => 'required',
                    // 'restaurant_id' => 'required',
             
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Products::where('status', 'Active')->where('restaurant_id',$request->id)->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
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
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/image/product/' .  Products::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/product/' . $_FILES['image']['name'])) {
                        $category = Products::where('id', $id)->update([
                            'name' => $request->name,
                            'category_id' => $request->category_id,
                           
                            // 'restaurant_id' => $request->restaurant_id,
                            'price' => $request->price,
                            'image' => $_FILES['image']['name'],
                            'description' => $request->description,
                            'price' => $request->price,
                        ]);
                        if ($category) {
                            // $flavour = FlavourProducts::where('product_id',$id)->delete();
                            // foreach($request->flavour_ids as $flavour){
                            //     FlavourProducts::create([
                            //         'product_id' => $id,
                            //         'flavour_id' => $flavour
                            //     ]);
                            // }
                            
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
                      
                        'description' => $request->description,
                        'price' => $request->price,
                        // 'restaurant_id' => $request->restaurant_id,
                    ]);
                    if ($category) {
                        // $flavour = FlavourProducts::where('product_id', $id)->delete();
                        // foreach ($request->flavour_ids as $flavour) {
                        //     FlavourProducts::create([
                        //         'product_id' => $id,
                        //         'flavour_id' => $flavour
                        //     ]);
                        // }
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

    public function search_products(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'search' => ['required'],
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        $products = Products::where('name', 'LIKE', '%' . $request->search . "%");
        if ($products->count() > 0) {
            return response()->json($products->with('category')->get(), 200);
        } else {
            return response()->json([
                "message" => "No record Found"
            ], 302);
        }
    }

    public function product_create_view(){
        // $restaurants = Restaurants::where('status','Active')->get();
        $categories =Categories::where('status', 'Active')->get();
        // $sub_categories = SubCategories::where('status', 'Active')->get();
        // $flavours = ProductFlavours::where('status', 'Active')->get();
        return view('admin.product-create',compact('categories'));
    }
    

    public function product_edit_view($id)
    {
        // $restaurants = Restaurants::where('status', 'Active')->get();
        $categories = Categories::where('status', 'Active')->get();
        // $sub_categories = SubCategories::where('status', 'Active')->get();
        // $flavours = ProductFlavours::where('status', 'Active')->get();
        $products = Products::where('status','Active')->where('id',$id)->with('category');
        if($products->count() <= 0) return redirect('/admin/products');
        $product = $products->first();
        return view('admin.product-edit', compact('restaurants', 'categories','product'));
    }


}
