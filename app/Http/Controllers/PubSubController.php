<?php

/**
 * Controller for Pub Sub system using HTTP Request
 *
 * PHP version 7,
 * Laravel 8
 *
 * @category  Controller
 * @author    Makinde Oluwatosin <makinde.oluwatosin.daniel@gmail.com>
 * @copyright 2021 Pangaea Challenge. All rights reserved.
 * @license   Unauthorized copying of this file, via any medium is highly prohibited.
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Topic;
use App\Models\PublishedEvent;
use Illuminate\Support\Facades\DB;

/**
 * Controller for Pub Sub system using HTTP Request
 *
 * PHP version 7,
 * Laravel 8
 *
 * @category  Controller
 * @author    Makinde Oluwatosin <makinde.oluwatosin.daniel@gmail.com>
 * @copyright 2021 Pangaea Challenge. All rights reserved.
 * @license   Unauthorized copying of this file, via any medium is highly prohibited.
 */
class PubSubController extends Controller
{
    /**
     * Subcribe to a 
     */
    public function subscribe(Request $request, $topic)
    {
        try {
            DB::beginTransaction();

            if (Topic::where('name', $topic)->exists()) {
                $subscribe = new Subscriber;
                $subscribe->topic_id = Topic::where('name', $topic)->first()->id;
                $subscribe->url = json_decode($request->getContent())->url;
                $subscribe->save();
            } else {
                $subTopic = new Topic;
                $subTopic->name = $topic;
                $subTopic->save();
    
                $subscribe = new Subscriber;
                $subscribe->topic_id = $subTopic->id;
                $subscribe->url = json_decode($request->getContent())->url;
                $subscribe->save();
            }
            DB::commit();
            $message = 'Subscription successful';
            $data = [
                "topic" => $topic,
                "url"  => $subscribe->url
            ];
            $result = $this->formatSuccessResponse($message, $data);
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result = $this->formatErrorResponse($th->getMessage());
            return $result;
        }
    }
     
    public function publish(Request $request, $topic)
    {
        try {
            DB::beginTransaction();
            $event = new PublishedEvent;
            $event->topic_id = Topic::where('name', $topic)->first()->id;
            $event->data = $request->getContent();
            $event->save();

            DB::commit();
            $message = 'Message published';
            $result = $this->formatSuccessResponse($message, $request->getContent());
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            $result = $this->formatErrorResponse($th->getMessage());
            return $result;
        }
    }

    public function event(Request $request)
    {
        $queryparam = $request->query('url');
        if ($queryparam) {
            $publishEvent = Subscriber::with('topic:id,name')->where('url', $queryparam)->select('id', 'topic_id', 'url')->get();
            $message = 'Success';
            $result = $this->formatSuccessResponse($message, $publishEvent);
            return $result;
        } else {
            $publishEvent = Subscriber::with('topic:id,name')->where('url', "http://localhost:8000/event")->select('id', 'topic_id', 'url')->get();
            $message = 'Success';
            $result = $this->formatSuccessResponse($message, $publishEvent);
            return $result;
        }
    }

    public function formatSuccessResponse($message, $data)
    {
        return response()->json(['status' => true, 'message' => $message, 'data' => $data], 200);
    }

    public function formatErrorResponse($th)
    {
        return response()->json(['status' => false, 'error' => $th], 403);
    }
}
