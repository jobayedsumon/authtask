<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

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

            if ($request->page_no) {
                $page_no = $request->page_no;
            } else {
                $page_no = 1;
            }

            $limit = $request->limit;
            $offset = ($page_no - 1) * $limit;
            $count = User::count();
            $total_page = ceil($count / $limit);

            $users = DB::table('users')
                ->offset($offset)
                ->limit($limit)
                ->get();

//            $users = User::paginate($limit);

            return view('users', compact('users', 'total_page', 'page_no', 'limit'));

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

    public function logout(Request $request)
    {
        $cookie = Cookie::forget('session_id');
        return response()->json(['msg' => 'Successfully logged out!'], 200)->withCookie($cookie);
    }

}
