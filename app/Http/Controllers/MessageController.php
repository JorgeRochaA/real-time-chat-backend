<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function insertMessage(MessageRequest $request)
    {
        $user = User::find($request->username_id);
        if ($user) {
            $userDatabase = auth()->user();
            if ($user->api_token == $request->token) {
                $message = [
                    "username" => $userDatabase->username,
                    "message" => $request->message,
                    "date" => $request->date,
                    "hour" => $request->hour,
                    "username_color" => $user->username_color
                ];
                Message::create($message);
                event(new MessageEvent(json_encode($message)));
                return response()->json(["success" => "message created"]);
            } else {
                return response()->json(["error" => "Unauthorized"]);
            }
        } else {
            return response()->json(["error" => "invalid user"]);
        }
    }

    public function getMessages()
    {
        return response()->json(Message::all(), 200);
    }
}
