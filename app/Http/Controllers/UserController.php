<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->all();

        if ($request->username && $request->email && $request->password && $request->username_color && $request->user_picture) {
            if (strlen($request->username) < 20) {
                $validateUsername = User::where("username", "=", $request->username)->first();
                if (!$validateUsername) {
                    $validateEmail = User::whereEmail($request->email)->first();
                    if (!$validateEmail) {
                        $user['password'] = Hash::make($request->password);
                        User::create($user);
                        $userRegistered = User::where("username", "=", $request->username)->first();
                        $userRegistered->api_token = Str::random(100);
                        $userRegistered->save();
                        return response()->json(["token" => $userRegistered->api_token,
                            "user" => ['username' => $userRegistered->username,
                                'username_color' => $userRegistered->username_color,
                                'user_picture' => $userRegistered->user_picture]]);
                    } else {
                        return response()->json(["error" => "email already registered"]);
                    }
                } else {
                    return response()->json(["error" => "username already registered"]);
                }

            } else {
                return response()->json(["error" => "username max length is 20 words"]);
            }
        } else {
            return response()->json(["error" => "Fill All The Fields"]);
        }
    }
}
