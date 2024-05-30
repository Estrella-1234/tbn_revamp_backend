<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FrontendRegisterController extends Controller
{
    /**
     * Register a new users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'user_role' => 'users', // Set the users role to 'users'
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'));
    }


    public function googleAuth(Request $request )
    {

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user', 'token'));
        } else {

            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' =>  $request->password,
                'user_role' => 'users',
            ]);

            if (!$user) {
                return response()->json(['error' => 'Failed to create user'], 500);
            }

            $token = JWTAuth::fromUser($user);
            $status = "success";
            return response()->json(compact('user', 'token','status'));

        }
    }
}
