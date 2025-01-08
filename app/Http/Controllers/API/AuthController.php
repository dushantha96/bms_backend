<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function signup(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed']
            ]);
            

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            // create the user account 
            User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'password' => $request['password'],
                'user_type' => 3
            ]);            
                      
            return $this->login($request);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }

    public function login(Request $request){
        try{            
            $input = $request->only('email', 'password');
            $jwt_token = null;
            if (!$jwt_token = JWTAuth::attempt($input)) {
                return response()->json([
                    'error' => 'Invalid Email or Password',
                ], 400);
            }
            $user = Auth::user();

            $jwt_token = JWTAuth::customClaims([
                'firstName' => $user->first_name, 
                'userType' => $user->user_type, 
            ])->fromUser($user);

            return response()->json([
                'status' => true,
                'token' => $jwt_token
            ], 200);
        }        
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }        
    }

    public function logout(Request $request){

        if(!(request()->bearerToken())){
            return response()->json([
             'message' => 'Token is required'
            ], 400);
        }
        
        try {
            JWTAuth::invalidate(JWTAuth::parseToken(request()->bearerToken()));
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully'
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function Refresh(Request $request){

        if(!(request()->bearerToken())){
            return response()->json([
             'message' => 'Token is required',
            ], 400);
        }
        
        try {
            $token = JWTAuth::refresh(request()->bearerToken());

            return response()->json([
                'status' => true,
                'token' => $token
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}