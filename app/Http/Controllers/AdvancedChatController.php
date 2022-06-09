<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class AdvancedChatController extends Controller
{
    public function index()
    {
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER')]);
        $result = $pusher->trigger('my-channel', 'my_event', 'hello world');
        dd($result);
        // return view('advanced-chat');
    }
}
