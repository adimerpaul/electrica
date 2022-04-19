<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            "token"=>$user->createToken('web')->plainTextToken,
            "user"=>$user
        ]);
    }
    public function me(Request $request){
        return $request->user();
    }
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }
}
