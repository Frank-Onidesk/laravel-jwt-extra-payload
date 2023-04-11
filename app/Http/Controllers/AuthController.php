<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\JWT;


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


        $login = JWTAuth::attempt($credentials);

        if (!$login) {
            //$this->refresh();
            return response()->json([
                'status' => 'error',
                'message' => 'Login credentials are wrong',
            ], Response::HTTP_UNAUTHORIZED); // 401
        }

        // authentificated user and payload settings

        $user = Auth::user();
        $setPayload = [ 'id' => $user->id,  '_wellow_is_awesome_' => true];

        $token = JWTAuth::claims($setPayload)->attempt($credentials);


        return response()->json([
            'status' => 'success',
            'message'  => 'Log in successfully',
            'user' => $user,
            'authorisation' => [
                'tooken' => compact('token'),
                'type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ], Response::HTTP_FOUND);
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

        ],Response::HTTP_CREATED);


        /*$payload = JWTFactory::make([
            'email' => $request->email,
            '_wellow_is_awesome_' => true
            ]
        );


        $token = Auth::login($user); */

        return response()->json([
            'status' => 'success',
            'message' => 'Registered successfully',
            'data' =>  $user

        ], Response::HTTP_OK);
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
            'message' => 'Token refreshed - security level was optimized',
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ], Response::HTTP_OK);
    }




    /**
     * middleware is supposed to redirect to login route i.e return route('login');
     * error often occurs when a route protected by a middleware is being accessed by an unauthorized/unauthenticated resource
     * @return  \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

       $token =  Auth::logout();
        return  response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
            'token' => $token

        ], Response::HTTP_OK);

        auth('api')->invalidate(true);
    }



    public function mood()
    {

        try {
            $user = auth('api')->userOrFail();
            $payload = auth()->payload();


        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            abort(Response::HTTP_NOT_FOUND);
        }



        return response()->json([
            'company_status' => $user,
            'payload' => $payload->toArray()

        ], Response::HTTP_OK);
    }
}
