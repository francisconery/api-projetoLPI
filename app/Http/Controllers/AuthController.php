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
        if (User::where("email", $request->input('email'))->get()->count() > 0){
            return response(json_encode(["status" => "Error" , "message" => "Duplicate email"]), 400)->header('Content-Type', 'application/json');
        }

        $user = new User();
        $user->name = $request->input('username');
        $user->email = $request->input('email');
        $user->password = hash('sha256', $request->input('password'));

        $user->save();
        return response(json_encode(["status" => "Ok" , "message" => "Created"]), 201)->header('Content-Type', 'application/json');
    }

    public function login(Request $request)
    {
        //hash('sha256', $request->input('password'))
        $result = User::where(
            [
                "email" => $request->input('email'),
                "password" => hash('sha256', $request->input('password')),
            ]
        )->get()->count();

        return response(json_encode(["status" => $result ? "Ok" : "Error" , "message" => $result ? "Welcome" : "Invalid credentials"]), $result ? 200 : 401)->header('Content-Type', 'application/json');
    }
}
