<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class AdvancedChat extends Model
{
    use HasFactory;

    public function CreateChat($eventName)
    {
        $data = DB::select(DB::raw(
            "select * from chat_table where event_name = ?"
        ), [ 
            $eventName, 
        ]);
        $isEventExist = count($data) > 0;

        if(!$isEventExist)
        {
            $data = DB::select(DB::raw(
                "insert into chat_table values(null, ?, ?)"
            ), [ 
                $this->channelName,
                $eventName, 
            ]); 

            $data = DB::select(DB::raw(
                "select * from chat_table where event_name = ?"
            ), [ 
                $eventName, 
            ]);

            return $data;
        }

        return $data;
    }

    public function StoreChat($eventName, $channelName, $sender, $message)
    {
        $data = DB::select(DB::raw(
            "insert into chat_history_table values(null, ?, ?, ?, ?)"
        ), [ 
            $sender,
            $message,
            $channelName,
            $eventName
        ]); 

        return $data;
    }

    public function GetChat($eventName)
    {
        $data = DB::select(DB::raw(
            "select sender, message from chat_history_table where event_name = ?"
        ), [ 
            $eventName
        ]); 

        return $data;
    }
}
