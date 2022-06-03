<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageNotification;

class SendMessageController extends Controller
{
    public function index($name, $message)
    {
        event(new MessageNotification($name, $message));

        return response()->json([
            'status' => 'success'
        ]);
    }
}
