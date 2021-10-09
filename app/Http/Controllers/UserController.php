<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function signUp(Request $request)
    {
        $user = $request->all();

        if ($request->username && $request->email && $request->password && $request->username_color) {
            if (strlen($request->username) < 20) {
                $validateUsername = User::where("username", "=", $request->username)->first();
                if (!$validateUsername) {
                    if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                        $validateEmail = User::whereEmail($request->email)->first();
                        if (!$validateEmail) {
                            $user['password'] = Hash::make($request->password);
                            User::create($user);
                            $userRegistered = User::where("username", "=", $request->username)->first();
                            $userRegistered->api_token = Str::random(100);
                            $userRegistered->save();
                            return response()->json([
                                "user" => ['id' => $userRegistered->id,
                                    'token' => $userRegistered->api_token,
                                    'username' => $userRegistered->username,
                                    'username_color' => $userRegistered->username_color]]);
                        } else {
                            return response()->json(["error" => "Email Already Registered"]);
                        }
                    } else {
                        return response()->json(["error" => "Put A Valid Email"]);
                    }
                } else {
                    return response()->json(["error" => "Username Already Registered"]);
                }

            } else {
                return response()->json(["error" => "Username Max Length Is 20 Letters"]);
            }
        } else {
            return response()->json(["error" => "Fill All The Fields"]);
        }
    }

    public function login(Request $request)
    {
        if ($request->email && $request->password) {
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $user = User::whereEmail($request->email)->first();
                if ($user) {
                    if (Hash::check($request->password, $user->password)) {
                        $user->api_token = Str::random(100);
                        $user->save();
                        return response()->json([
                            "user" => ['id' => $user->id,
                                'token' => $user->api_token,
                                'username' => $user->username,
                                'username_color' => $user->username_color]]);
                    } else {
                        return response()->json(["error" => "Password Don't Match"]);
                    }
                } else {
                    return response()->json(["error" => "Email Not Match With Our Records"]);
                }
            } else {
                return response()->json(["error" => "Put A Valid Email"]);
            }
        } else {
            return response()->json(["error" => "Fill All The Fields"]);
        }
    }


    function logout()
    {
        $user = auth()->user();
        $user->api_token = null;
        $user->save();
        return response()->json(['success' => 'GoodBye! '.$user->username], 200);
    }
}
