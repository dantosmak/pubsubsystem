<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PubSubController extends Controller
{
    public function publish(Request $request, $topic)
    {
        Redis::publish($request->getContent(), $topic);

        $message = 'Message published';
        $result = $this->formatSuccessResponse($message, $request->getContent());
        return $result;
    }

    public function formatSuccessResponse($message, $data)
    {
        return response()->json(['status' => true, 'message' => $message, 'data' => $data], 200);
    }
}
