<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function sign_up(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string',
        //     'email' => 'required|string|unique:users,email',
        //     'password' => 'required|string|confirmed'


        // ]);

        // $user = User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => bcrypt($data['password'])

        // ]);

        $rules = [
            'name' => 'unique:users|required',
            'email'    => 'unique:users|required',
            'password' => 'required',
        ];

        $input     = $request->only('name', 'email', 'password');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->messages()
            ]);
        }
        $name = $request->name;
        $email    = $request->email;
        $password = $request->password;
        $user     = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        $token    = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token

        ];

        return response($res, 201);
    }
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response([
                'msg' => 'user not found'
            ], 401);
        } else if (!Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'incorrect username or password'
            ], 401);
        }
        $token = $user->createToken('apiToken')->plainTextToken;
        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response($res, 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->logout();
        // auth()->user()->currentAccessToken()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
