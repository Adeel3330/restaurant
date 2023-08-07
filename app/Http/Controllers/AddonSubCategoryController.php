<?php

namespace App\Http\Controllers;

use App\Models\AddonSubCategory;
use Illuminate\Http\Request;

class AddonSubCategoryController extends Controller
{
    //
    public function addon_sub_categories($id = null)
    {
        if (!$id) {
            $category = AddonSubCategory::with('addon_category', 'restaurant')->where('status', 'Active');
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
            $category = AddonSubCategory::with('addon_category', 'restaurant')->where('status', 'Active')->where('id', $id);
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
}
