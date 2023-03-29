<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:60',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login credentials are wrong',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            '_wellow_is_awesome_'  => true,
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'status' => 'register_success',

            'authorization' => [
                'token' => $token,
                'type' => 'bearer'
            ]

        ]);
    }



    /**
     * Generate a new access token
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }




    /**
     * middleware is supposed to redirect to login route i.e return route('login');
     * error often occurs when a route protected by a middleware is being accessed by an unauthorized/unauthenticated resource
     * @return  \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        Auth::logout();
        return  response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',

        ]);
    }
}
