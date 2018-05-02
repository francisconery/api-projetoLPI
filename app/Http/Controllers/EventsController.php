<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Event;
use AuthController;
use App\User;

class EventsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function createEvent(Request $request)
    {
        $result = Validator::make($request->all(), [
            'name' => 'required|string',
            'about' => 'required',
            'image' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'api_token' => 'required',
        ]);
        
        if ($result->fails()) {
            return response(json_encode(["status" => "Error" , "message" => $result->messages()]), 400)->header('Content-Type', 'application/json');
        }
        
        if (($user_id = $this->getUserFromToken($request->input('api_token'))) === null) {
            return response(json_encode(["status" => "Error" , "message" => "Unauthorized"]), 401)->header('Content-Type', 'application/json');
        }

        $event = new Event();
        $event->user_id = $user_id;
        $event->name = $request->input('name');
        $event->about = $request->input('about');
        $event->profilePic = $request->input('image');
        $event->latitude = $request->input('latitude');
        $event->longitude = $request->input('longitude');
        $event->save();

        return response(json_encode(["status" => "Ok" , "message" => "Created"]), 201)->header('Content-Type', 'application/json');
    }

    public function getEvents()
    {
        $events = Event::all(['name', 'name', 'about', 'profilePic' ,'latitude', 'longitude', 'updated_at'])->toArray();
        return response(json_encode(["status" => "Ok" , "message" => $events]), 200)->header('Content-Type', 'application/json');
    }

    private function getUserFromToken($token)
    {
        $result = User::where(
            [
                "api_token" => $token,
            ]
        )->first();

        return $result ? $result->id : null;
    }
}
