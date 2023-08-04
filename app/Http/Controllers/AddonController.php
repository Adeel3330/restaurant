<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    //
    public function addons($id = null)
    {
        if (!$id) {
            $category = Addon::where('status', 'Active');
            if ($category->count() > 0) {

                return response()->json(
                    $category->with('category')->with('sub_category', 'restaurant', 'flavour_ids')->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No Addon found"
                ], 302);
            }
        } else {
            $category = Addon::with('category', 'sub_category', 'restaurant', 'flavour_ids')->where('status', 'Active')->where('id', $id);
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No Addon found"
                ], 302);
            }
        }
    }
}
