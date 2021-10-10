<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function insertMessage(Request $request)
    {
        if ($request->message && $request->date && $request->hour && $request->username_id && $request->token) {
            $user = User::find($request->username_id); // validate if the user exist
            if ($user) {
                if ($user->api_token == $request->token) { // validate token
                    if (strlen($request->message) <= 148) {
                        if (strlen($request->date) <= 10) {
                            if (strlen($request->hour) <= 8) {
                                $message = ["username" => $user->username,
                                    "message" => $request->message, "date" => $request->date, "hour" => $request->hour,
                                    "username_color" => $user->username_color];
                                Message::create($message);
                                event(new MessageEvent(json_encode($message)));
                                return response()->json(["success" => "message created"]);
                            } else {
                                return response()->json(["error" => "Hour Max Length Is 8 Letters"]);
                            }
                        } else {
                            return response()->json(["error" => "Date Max Length Is 10 Letters"]);
                        }
                    } else {
                        return response()->json(["error" => "Message Max Length Is 148 Letters"]);
                    }
                } else {
                    return response()->json(["error" => "invalid token"]);
                }
            } else {
                return response()->json(["error" => "Invalid ID"]);
            }
        } else {
            return response()->json(["error" => "Fill All The Filds"]);
        }
    }

    public function getMessages(){
        return response()->json(Message::all(),200);
    }
}
