<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $loginAfterSignUp=false;

    public function login(Request $request)
    {
       $credentials=$request->only("email","password");
       $token=null;

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "status"=>false,
                    "messsage"=>"Unauthorized"
                ]);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json([
            "status"=>true,
            "token"=>$token
        ]);
    }
    public function register(Request $request){
        $this->validate($request,[
            "email"=>"required",
            "password"=>"required"
        ]);
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->gender=$request->gender;
        $user->save();

        if($this->loginAfterSignUp){
            return $this->login($request);
        }
        return response()->json([
            "status"=>true,
            "user"=>$user
        ]);

    }
    public function logout(Request $request)
    {
        $this->validate($request,[
            "token"=>"required",
        ]);

        try{
            JWTAuth::invalidate($request->token);
            return response()->json([
                "status"=>true,
                "message"=>"User Logged Out Successfully"
            ]);
        }
        catch(JWTException $exception){
            return response()->json([
                "status"=>false,
                "message"=>"OOps. user not logged out"
            ]);
        }
    }
}
