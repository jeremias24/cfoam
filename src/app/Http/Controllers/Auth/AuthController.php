<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Str;

use Illuminate\Testing\Fluent\Concerns\Has;

use Illuminate\Validation\Rules\Password;

use App\Services\Service;

use App\Models\User;
use App\Models\UserVw;

use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct(User $user, UserVw $userVw, Service $service)
    {
        $this->user = $user;
        $this->userVw = $userVw;
        $this->service = $service;
    }
    
    public function login(Request $request)
    {
        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response()->json([
                'errors' => 'The Provided credentials are not correct'
            ], 422);
        }

        $user = Auth::user();
        $api_token = $user->createToken('main')->plainTextToken;
        $request->user()->api_token = $api_token;
      
        return response()->json([
            'user' => $request->user()
        ]);
    } 

    public function verifyToken(Request $request) 
    {
        $user = Auth::user();
        $request->user()->api_token = $request->api_token;
      
        return response()->json([
            'user' => $request->user(),
            'api_token' => $request->api_token
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }
}
