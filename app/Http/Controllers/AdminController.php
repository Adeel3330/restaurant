<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function delete_user($id){
            $users = Users::where('id', $id)->where('status', 'Active');
            if ($users->count() == 1) {
                $update = $users->update(['status' => 'delete']);
                if ($update) {
                    return response()->json([
                        'mesage' => "User Delete successfully"
                    ], 200);
                } else {
                    return response()->json([
                        'mesage' => "Something Went wrong"
                    ], 302);
                }
            } else {
                return response()->json([
                    'mesage' => "Invalid Id or record already deleted"
                ], 302);
            }
        }

    }
