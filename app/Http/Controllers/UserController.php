<?php

namespace App\Http\Controllers;

use App\Events\InfoEvent;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function signUp(UserRequest $request)
    {
        $user = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username_color' => $request->username_color,
            'api_token' => Str::random(100),
        ];

        $userRegistered =  User::create($user);
        if ($userRegistered) {
            event(new InfoEvent($userRegistered->username . " Join The Room"));
            return response()->json([
                "user" => [
                    'id' => $userRegistered->id,
                    'token' => $userRegistered->api_token,
                    'username' => $userRegistered->username,
                    'username_color' => $userRegistered->username_color
                ]
            ]);
        } else {
            return response()->json([
                'message' => 'There was an error registering the user'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validateCredentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validateCredentials) {
            $user = User::whereEmail($request->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $user->api_token = Str::random(100);
                    $user->save();
                    event(new InfoEvent($user->username . " Join The Room"));
                    return response()->json([
                        "user" => [
                            'id' => $user->id,
                            'token' => $user->api_token,
                            'username' => $user->username,
                            'username_color' => $user->username_color
                        ]
                    ]);
                } else {
                    return response()->json(["error" => "wrong credentials"], 401);
                }
            } else {
                return response()->json(["error" => "wrong credentials"], 401);
            }
        } else {
            return response()->json([
                'message' => 'wrong credentials'
            ], 401);
        }
    }


    function logout()
    {
        $user = auth()->user();
        $user->api_token = null;
        $user->save();
        event(new InfoEvent($user->username . " Left The Room"));
        return response()->json(['success' => 'GoodBye! ' . $user->username], 200);
    }
}
