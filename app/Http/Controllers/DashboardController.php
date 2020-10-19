<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $user;

    public function __construct()
    {
        //$this->user=JWTAuth::parseToken()->authenticate();
        try {
            if (! $this->user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        
            return response()->json(['token_expired'], $e->getStatusCode());
        
        }

    }

    public function profile(Request $request)
    {
        return response()->json([
            "status"=>true,
            "user"=>$this->user
        ]);
    }
}
