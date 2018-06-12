<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
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

    public function register(Request $request)
    {
        if (User::where("email", $request->input('email'))->get()->count() > 0) {
            return response(json_encode(["status" => "Error" , "message" => "Duplicate email"]), 400)->header('Content-Type', 'application/json');
        }

        $user = new User();
        $user->name = $request->input('username');
        $user->email = $request->input('email');
        $user->api_token = str_random(60);
        $user->password = hash('sha256', $request->input('password'));
        $user->level = 2;

        $user->save();
        return response(json_encode(["status" => "Ok" , "message" => $user->api_token]), 201)->header('Content-Type', 'application/json');
    }

    public function login(Request $request)
    {
        $user = User::where(
            [
                "email" => $request->input('email'),
                "password" => hash('sha256', $request->input('password')),
            ]
        )->first();
        
        if ($user) {
            $user->api_token = str_random(60);
            $user->save();
        }

        return response(json_encode(["status" => $user ? "Ok" : "Error" , "message" => $user ? $user->api_token : "Invalid credentials"]), $user ? 200 : 401)->header('Content-Type', 'application/json');
    }
}
