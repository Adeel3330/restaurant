<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\addons;
use App\Models\Categories;
use App\Models\Restaurants;
use Illuminate\Http\Request;
use App\Models\addonFlavours;
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
            'sub_category_id' => 'required',
            'restaurant_ids' => 'required',
            'name' => ['required', Rule::unique('addons')->where('restaurant_id', $request->restaurant_id)->where('status', 'Active')],
            'image' => ['required', Rule::imageFile()],
            'price' => 'required',
            'description' => 'required',
            'flavour_ids' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 302);
        } else {
            if ($_FILES['image']['size'] > 2000000) {
                return response()->json([
                    "message" => "Max file size is 2mb"
                ], 302);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/addon/' . $_FILES['image']['name'])) {
                    foreach ($request->restaurant_ids as $restaurant_id) {

                        $category = new Addon();
                        $category->name = $request->name;
                        $category->category_id = $request->category_id;
                        $category->restaurant_id = $restaurant_id;
                        $category->sub_category_id = $request->sub_category_id;
                        $category->image = $_FILES['image']['name'];
                        $category->description = $request->description;
                        $category->price = $request->price;
                        $category->status = "Active";
                        $result =    $category->save();
                        $addon_id = Addon::where('status', 'Active')->orderBy('id', 'desc')->first()['id'];
                        foreach ($request->flavour_ids as $flavour) {
                            FlavourAddons::create([
                                'addon_id' => $addon_id,
                                'flavour_id' => $flavour,
                            ]);
                        }
                    }
                    if ($result) {

                        return response()->json([
                            "message" => "Addon created successfully"
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
        $addons = Addon::where('status', 'Active')->get();
        return view('admin.addons', compact('addons'));
    }



    public function edit_addon(Request $request, $id)
    {
        if (Addon::where('status', 'Active')->where('id', $id)->count() > 0) {
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $validator = Validator::make($request->all(), [
                    'category_id' => ['required'],
                    'sub_category_id' => 'required',
                    'name' => ['required'],
                    'restaurant_id' => 'required',
                    'image' => ['required', Rule::imageFile()],
                    'description' => 'required',
                    'price' => 'required',
                    'flavour_ids' => 'required',

                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => ['required'],
                    'category_id' => 'required',
                    'sub_category_id' => 'required',
                    'description' => 'required',
                    'price' => 'required',
                    'restaurant_id' => 'required',
                    'flavour_ids' => 'required'
                ]);
            }
            //  dd(Categories::where('status', 'Active')->where('id', '!=', $id)->count());  
            if (Addon::where('status', 'Active')->where('restaurant_id', $request->id)->where('id', '!=', $id)->where('name', 'LIKE', $request->name)->count() > 0) {
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
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/image/addon/' .  Addon::where('id', $id)->first()->image);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/image/addon/' . $_FILES['image']['name'])) {
                        $category = Addon::where('id', $id)->update([
                            'name' => $request->name,
                            'category_id' => $request->category_id,
                            'sub_category_id' => $request->sub_category_id,
                            'restaurant_id' => $request->restaurant_id,
                            'price' => $request->price,
                            'image' => $_FILES['image']['name'],
                            'description' => $request->description,
                            'price' => $request->price,
                        ]);
                        if ($category) {
                            $flavour = FlavourAddons::where('addon_id', $id)->delete();
                            foreach ($request->flavour_ids as $flavour) {
                                FlavourAddons::create([
                                    'addon_id' => $id,
                                    'flavour_id' => $flavour
                                ]);
                            }

                            return response()->json([
                                "message" => "Addon updated successfully"
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
                    $category = Addon::where('id', $id)->update([
                        'name' => $request->name,
                        'category_id' => $request->category_id,
                        'sub_category_id' => $request->sub_category_id,
                        'description' => $request->description,
                        'price' => $request->price,
                        'restaurant_id' => $request->restaurant_id,
                    ]);
                    if ($category) {
                        $flavour = FlavourAddons::where('addon_id', $id)->delete();
                        foreach ($request->flavour_ids as $flavour) {
                            FlavourAddons::create([
                                'addon_id' => $id,
                                'flavour_id' => $flavour
                            ]);
                        }
                        return response()->json([
                            "message" => "Addons updated successfully"
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
            return response()->json($addons->with('restaurant', 'category', 'sub_category')->get(), 200);
        } else {
            return response()->json([
                "message" => "No record Found"
            ], 302);
        }
    }

    public function addon_create_view()
    {
        $restaurants = Restaurants::where('status', 'Active')->get();
        $categories = Categories::where('status', 'Active')->get();
        $sub_categories = SubCategories::where('status', 'Active')->get();
        $flavours = ProductFlavours::where('status', 'Active')->get();
        return view('admin.addon-create', compact('restaurants', 'categories', 'sub_categories', 'flavours'));
    }


    public function addon_edit_view($id)
    {
        $restaurants = Restaurants::where('status', 'Active')->get();
        $categories = Categories::where('status', 'Active')->get();
        $sub_categories = SubCategories::where('status', 'Active')->get();
        $flavours = ProductFlavours::where('status', 'Active')->get();
        $addons = Addon::where('status', 'Active')->where('id', $id)->with('restaurant', 'category', 'sub_category', 'flavour_ids');
        if ($addons->count() <= 0) return redirect('/admin/addons');
        $addon = $addons->first();
        // dd($addon);
        return view('admin.addon-edit', compact('restaurants', 'categories', 'sub_categories', 'addon', 'flavours'));
    }

}
