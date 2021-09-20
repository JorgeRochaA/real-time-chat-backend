<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserController extends Controller
{
    public function store(Request $request){
         $user = $request->all();

         if ($request->username && $request->email && $request->password && $request->username_color && $request->user_picture ){
              if (strlen($request->username) < 20){
                $validateUsername = User::find($request->username);
                if (!$validateUsername){
                    $validateEmail = User::find($request->email);
                    if (!$validateUsername){
                        $user['password'] = Hash::make($request->password);
                        User::create($user);
                    }else{
                        return response()->json(["error"=>"email already registered"]);
                    }
                }else{
                    return response()->json(["error"=>"username already registered"]);
                }
              }else{
                  return response()->json(["error"=>"username max length is 20 words"]);
              }
         }else{
             return response()->json(["error"=>"Fill All The Fields"]);
         }
    }
}
