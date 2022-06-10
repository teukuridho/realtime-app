<?php

namespace App\Http\Controllers;

use App\Models\AdvancedChat;
use Validator;
use Illuminate\Http\Request;
use Pusher\Pusher;
use DB;

class AdvancedChatController extends Controller
{

    private $pusher;
    private $channelName = "presence-channel";

    public function __construct()
    {
        $this->pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER')]);
    }

    public function index()
    {
        // $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER')]);
        
        // $result = $pusher->trigger('my-channel', 'my_event', 'hello world');
        // dd($result);
        return view('advanced-chat');
    }

    public function pusher_auth(Request $request)
    {
        $input = $request->all();

        $username = $request->header('username');
        $customMessages = [
            'required' => ':attribute masih kosong!',
        ];

        $validator = Validator::make($input, [
            'socket_id' => 'required',
            'channel_name' => 'required',
        ], $customMessages); 

        if($validator->passes())
        {
            $result = $this->pusher->presenceAuth($input['channel_name'], $input['socket_id'], $username);

            return $result;
            
        }
        else
        {
            return response()->json(['error'=>$validator->errors()], 403);
        }
    }

    public function send_message($name, $message, $event)
    {
        (new AdvancedChat())->StoreChat($event, $this->channelName, $name, $message);

        $result = $this->pusher->trigger($this->channelName, $event, [
            'name' => $name,
            'message' => $message
        ]);

        return $result;
    }

    public function get_messages($event)
    {
        $result = (new AdvancedChat())->GetChat($event);

        return $result;
    }
}
