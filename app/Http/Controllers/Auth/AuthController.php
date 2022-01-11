<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use illuminate\Http\Response;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        try{
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $token = $user->createToken('User Access Token')->plainTextToken;

            DB::commit();

            return response()->json([
                "data"=>compact('token','user'),
                "success" => true,
                "message" => 'Registration Successfull.',
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "success" => false,
                'message' => $e->getMessage(),
            ],417);
        }
    }

    public function login(LoginRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $user = User::where('email', $request->email)->first();

        if (Auth::attempt($data)) {
            $token = auth()->user()->createToken('User Access Token')->plainTextToken;
            $user = auth()->user()->first();
            return response()->json([
                "data"=>compact('token','user'),
                "success" => true,
                "message" => 'Logged In Successfully.',
            ],200);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'Credentials does not match.',
            ],401);
        }
    }

    public function logout(Request $request){
        try{

            Auth::user()->tokens()->delete();

            return response()->json([
                "success" => true,
                'message' => 'Logged out'
            ,200]);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                'message' => $e->getMessage(),
            ],417);
        }
    }
}
