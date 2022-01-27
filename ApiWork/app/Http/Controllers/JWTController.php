<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JWTController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['login','register']]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|string',
            'email'=>'required|string|email|unique:user',
            'password'=>'required|string|min:8'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors());
        }
        else
        {
            // $user = User::create([
            //     'name'=>$request->name,
            //     'email'=>$request->email,
            //     'password'=>Hash::make($request->password)
            // ]);

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
 
            if($user->save())
            {return response()->json([
                'message'=>'User create successfully',
                'user'=>$user
           ],201);}

           else
           {
              return response()->json(['msg'=>'user not created!']);
           }
            
        }

        
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            
            'email'=>'required|string|email|unique:user',
            'password'=>'required|string|min:8'
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors());
        }
        else
        {
            if(!$token=auth()->attempt($validator->validated()))
            {
                return response()->json(['error'=>'Unauthorised']);
            }
            else
            {
                return $this->respondWithToken($token);
            }
        }

    }

    public function respondWithToken($token)
    {
        return response()->json([
             'access_token'=>$token,
             'token_type'=>'bearer',
             'expires_in'=>auth()->factory()->getTTL()*60,
        ]);
    }
}
