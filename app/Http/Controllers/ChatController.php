<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Teacher,Headteacher']);
    }

    public function index()
    {
        return view('chat.index');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $message = $request->input('message');

        broadcast(new MessageSent($user, $message))->toOthers();

        return response()->json(['status' => 'Message sent!']);
    }
}
