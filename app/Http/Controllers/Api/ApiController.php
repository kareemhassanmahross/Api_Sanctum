<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class ApiController extends Controller
{
    public function register(Request $req){
       $req->validate([
         "name"=>"required|string",
         "email"=>"required|string|email|unique:users",
         "password"=>"required|confirmed"
       ]);
       User::create([
        "name"=>$req->name,
        "email"=>$req->email,
        "password"=>bcrypt($req->password)
       ]);
       return response()->json([
           "status"=>true,
           "Massage"=>"User Registered Successfully"
       ]);
    }
    public function login(Request $req){
        $req -> validate([
            'email'=>'required|string|email',
            'password' => 'required'
        ]);

        $user = User::where('email' , $req->email)->first();
        // dd($user);
        if(!empty($user)){

            if(Hash::check($req->password , $user->password)){
                
                $token = $user->createToken('loginTokenEmail'.$user->email)->plainTextToken;

                return response()->json([
                    'status' => true,
                    'token' => $token,
                    'Massage' => 'User Logged in Successfully' 
                ]);

            }else{
                return response()->json([
                 "status"  => false,
                 "Massage" => "Wrong Password !"
                ]);
            }

        }else{
            return response()->json([
                "status" => false,
                'Massage'=> "Email and password is in valid"
            ]);
        }
    }
    public function logout(){
            auth()->user()->tokens()->delete();
          return response([
            'status' => true,
            'massage' => 'user logged out' 
          ]);
        }
    public function profile(){
        $userData = Auth()->user();

         return response()->json([
             'status' => true,
             'Massage'=> 'Profile information',
             'Data' => $userData
         ]);
        

    }
}
