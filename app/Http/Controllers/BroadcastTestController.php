<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;

class BroadcastTestController extends Controller
{
    public function send(Request $request)
    {
        $message = $request->input('message', 'Olá mundo!');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'Mensagem enviada via WebSocket!']);
    }
}
