<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\AddonCategory;
use App\Models\addons;
use App\Models\Categories;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Models\addonFlavours;
use App\Models\AddonSubCategory;
use App\Models\Flavouraddons;
// use App\Models\FlavourAddons;
use App\Models\ProductFlavours;
use App\Models\SubCategories;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AddonControllerWeb extends Controller
{
    //
    public function addon_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'name' => ['required', Rule::unique('addons')->where('status', 'Active')],
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
           
                        $category = new Addon();
                        $category->name = $request->name;
                        $category->category_id = $request->category_id;
                        $category->price = $request->price;
                        $category->status = "Active";
                        $result =    $category->save();
                 
                    if ($result) {

                        return response()->json([
                            "message" => "Addon created successfully"
                        ], 200);
                    } else {
                        return response()->json([
                            "message" => "Something went wrong"
                        ], 302);
                    }
                
        }
    }

    public function delete_addon($id = null)
    {
        if (!$id) {
            return response()->json([
                "message" => "Please enter the id of addon for deleting"
            ], 302);
        }
        if (Addon::where('id', $id)->where('status', 'Active')->count() > 0) {
            $addons = Addon::where('id', $id)->update([
                'status' => 'delete'
            ]);
            if ($addons) {

                return response()->json([
                    "message" => "Addon deleted successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "Something went wrong!"
                ], 302);
            }
        } else {
            return response()->json([
                "message" => "Addon not found or already deleted"
            ], 302);
        }
    }
    public function addons($id = null)
    {
        $addons = Addon::where('status', 'Active')->with('category')->get();
        // dd($addons);
        return view('admin.addons', compact('addons'));
    }



    public function edit_addon(Request $request, $id)
    {
        if (Addon::where('status', 'Active')->where('id', $id)->count() > 0) {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'category_id' => 'required', 
                    'price' => 'required',
                ]);
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Addon::where('status', 'Active')->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
                return response()->json([
                    "message" => "The name has already been taken"
                ], 302);
            }
            if ($validator->fails()) {
                return response()->json($validator->errors(), 302);
            } else {
                $category = Addon::where('id', $id)->update([
                            'name' => $request->name,
                            'category_id' => $request->category_id,
                            'price' => $request->price,
                        ]);
                        if ($category) {
                            return response()->json([
                                "message" => "Addon updated successfully"
                            ], 200);
                        } else {
                            return response()->json([
                                "message" => "Something went wrong"
                            ], 302);
                        }                
            }
        } else {
            return response()->json([
                "message" => "addon not exists"
            ], 302);
        }
    }

    public function search_addons(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'search' => ['required'],
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        }
        $addons = Addon::where('restaurant_id', $request->id)->where('name', 'LIKE', '%' . $request->search . "%");
        if ($addons->count() > 0) {
            return response()->json($addons->with('restaurant', 'category')->get(), 200);
        } else {
            return response()->json([
                "message" => "No record Found"
            ], 302);
        }
    }

    public function addon_create_view()
    {
        $categories = AddonCategory::where('status', 'Active')->get();   
        return view('admin.addon-create', compact('categories'));
    }


    public function addon_edit_view($id)
    {
        // $restaurants = Restaurants::where('status', 'Active')->get();
        $categories = AddonCategory::where('status', 'Active')->get();
        // $sub_categories = AddonSubCategory::where('status', 'Active')->get();

        $addons = Addon::where('status', 'Active')->where('id', $id)->with('category');
        if ($addons->count() <= 0) return redirect('/admin/addons');
        $addon = $addons->first();
        // dd($addon);
        return view('admin.addon-edit', compact( 'categories', 'addon'));
    }

}
