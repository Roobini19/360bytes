<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Mail;
use Config;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()) {
            return response()->json($request->errors(), 422);
        }

        if(! $token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['error' => 'Either email or password is wrong.'], 401);
        };

        return $this->createNewToken($token);
    }

    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $request['password'] = bcrypt($request->password);
        $User = User::create($request->all());

        return response()->json(["message" => "User Created Successfully!", "user" => $User]);
    }

    public function createNewToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => env("JWT_TTL", 60) * 60,
            'user' => auth()->user()
        ]);
    }

    public function userProfile() {
        if(Auth::guard('api')->user() != null) {
            $user = User::where('id', Auth::guard('api')->user()->id)->with('loans')->get();

            return response()->json($user);
        } else {
            return response()->json(['message' => 'Token Expired.Please login again!']);
        }      
    }

    public function logout() {
        if(Auth::guard('api')->check()) {
            Auth::guard('api')->logout();
        }

        return response()->json('User logged out successfully!');
    }
}