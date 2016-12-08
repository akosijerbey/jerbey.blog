<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Request;
use LRedis;

class chatController extends Controller
{
    public function sendMessage()
    {
    	$redis = LRedis::connection();
    	$data = ['message' => Request::input('message'), 'user' => Request::input('user')];
    	$redis->publish('message', json_encode($data));

		return response()->json([]);
    }

    public function createRoom()
    {
    	$redis = LRedis::connection();
    	$data = ['room' => Request::input('room'), 'user' => Request::input('user')];
    	$redis->publish('room', json_encode($data));

		return response()->json([]);
    }
}
