<?php

namespace App\Http\Controllers;

use App\Models\AddonCategory;
use Illuminate\Http\Request;

class AddonCategoryController extends Controller
{
    //
    public function addon_categories($id = null)
    {
        if (!$id) {
            $category = AddonCategory::with('restaurant')->where('status', 'Active');
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No category found"
                ], 302);
            }
        } else {
            $category = AddonCategory::with('restaurant')->where('status', 'Active')->where('id', $id);
            if ($category->count() > 0) {

                return response()->json(
                    $category->get(),
                    200
                );
            } else {
                return response()->json([
                    "message" => "No category found"
                ], 302);
            }
        }
    }
}
