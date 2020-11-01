<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Cookie;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login_form(Request $request)
    {
        if ($request->cookie('session_id')) {
            return redirect('/users');
        } else {
            return view('login');
        }

    }

    public function register_form()
    {
        return view('register');
    }

    public function users(Request $request)
    {
        if ($request->cookie('session_id')) {
            return view('users');
        } else {
            return redirect('/login');
        }
    }

    public function register(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'email' => 'required|unique:users',
           'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if ($user) {
            return response()->json(['msg', 'User created successfully!'], 200);
        } else {
            return response()->json(['msg', 'User creation failed!'], 404);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ])->withCookie('session_id', $token);
    }

}
