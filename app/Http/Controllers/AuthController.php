<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
       try{
            if(User::where('email', $request->email)->first()){
                return response([
                    'message' => 'Email already exists',
                    'status'=> 422
                ], 422);
            }
            $validator = Validator::make($request->all(),[
                'email'=>'required|email',
                'password'=>'required|min:6'
            ]);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>422,
                    'error'=>$validator->messages()
                ],422);
            }
            $user = User::create([
                'email'=> $request->email,
                'password'=> Hash::make( $request->password),
            ]);
            $token = $user->createToken($request->email)->plainTextToken;
            return response()->json([
                'token'=>$token,
                'status'=>200,
                'message'=>'Account created successfully'
            ],200);
       }
       catch(\Exception $e)
       {
            return response()->json([
                'status'=>500,
                'message'=>'Something went wrong...'
            ],500);
        }
    }
    public function login(Request $request)
    {
       try{
            $validator = Validator::make($request->all(),[
                'email'=>'required|email',
                'password'=>'required|min:6'
            ]);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>422,
                    'error'=>$validator->messages()
                ],422);
            }
            $user = User::where('email',$request->email)->first();
            if($user && Hash::check($request->password, $user->password))
            {
                $token = $user->createToken($request->email)->plainTextToken;
                return response()->json([
                    'status'=>200,
                    'message'=>'Login success',
                    'token'=>$token
                ],200);
            }
            return response()->json([
                'status'=>401,
                'message'=>'Invalid credentials'
            ],401);
       }
       catch(\Exception $e)
       {
         return response()->json([
            'status'=>500,
            'message'=>'Something went wrong...'
         ],500);
       }
    }
    public function logged_user_data()
    {
        try{
            $user = auth()->user();
            return response()->json([
                'status'=>200,
                'data'=>$user,
            ]);
        }
        catch(\Exception $e)
        {
          return response()->json([
             'status'=>500,
             'message'=>'Something went wrong...'
          ],500);
        }
    }
    public function change_password(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'password'=>'required|confirmed|min:6'
            ]);
            if($validator->fails())
            {
                return response()->json([
                    'status'=>422,
                    'error'=>$validator->messages()
                ],422);
            }
            $loggedInUser = auth()->user();
            $loggedInUser->password = Hash::make($request->password);
            $loggedInUser->save();
            return response()->json([
                'status'=>200,
                'message'=>'Password changed successfully'
            ],200);
        }
        catch(\Exception $e)
        {
          return response()->json([
             'status'=>500,
             'message'=>'Something went wrong...'
          ],500);
        }
    }
    public function logout()
    {
        try{
            auth()->user()->tokens()->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Logout success'
             ],200);
        }
        catch(\Exception $e)
        {
          return response()->json([
             'status'=>500,
             'message'=>'Something went wrong...'
          ],500);
        }
    }
}
