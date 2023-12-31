<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name'  => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        $user = User::create([
           'name' => $validateData['name'],
           'email' => $validateData['email'],
           'password' => Hash::make($validateData['password']),
            'role'    => 'user'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
                'access_token' =>$token,
                'user'         => UserResource::make($user)
            ]);


        // $validateData['password'] = bcrypt($request->password);

        // $user = User::create($validateData);

        // $accessToken = $user->createToen('authToken')->accessToken;

        // return response(['user' => $user,' acccess_token' => $accessToken]);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email'  => 'email|required',
            'password' => 'required'
        ]);

        $user = User::where('email', $loginData['email'])->first();

        if(!$user){
            return response()->json([
                'message' => 'user not found'
            ], 401);
        }

        if(!Hash::check($loginData['password'], $user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' =>$token,
            'user'         => UserResource::make($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logut success'
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
