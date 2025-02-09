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
use App\Models\UserEmployeeDetailsVw;
use App\Models\UserSystemAccessesVw;

use Carbon\Carbon;

class AuthController extends Controller
{
    public function __construct(User $user, UserEmployeeDetailsVw $userEmployeeDetailsVw, UserSystemAccessesVw $userSystemAccessesVw, UserVw $userVw, Service $service)
    {
        $this->user = $user;
        $this->userEmployeeDetailsVw = $userEmployeeDetailsVw;
        $this->userSystemAccessesVw = $userSystemAccessesVw;
        $this->userVw = $userVw;
        $this->service = $service;
    }
    
    public function login(Request $request)
    {

        //  return ['test' => $request->all()];
        // try {
        //     DB::connection()->getPdo();
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Successfully connected to the database.',
        //         'database' => DB::connection('mysql')->getDatabaseName(),
        //         'driver' => DB::connection()->getDriverName()
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to connect to the database.',
        //         'error' => $e->getMessage(),
        //         'driver' => config('database.default'),
        //         'connections' => config('database.connections')
        //     ], 500);
        // }
    
        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response()->json([
                'errors' => 'The Provided credentials are not correct'
            ], 422);
        }

        $user = Auth::user();

        // if($request->user()->password_expiration <= Carbon::now()) {

        //     $user = User::where('id', $request->user()->id)->get();

        //     return response()->json([
        //         "errors" => "Password expired",
        //         "user" =>  $request->user()
        //     ], 401);
        // }

        // $request->user()->accesses = $this->userAccesses($user);

        // if(count($request->user()->accesses) == 0) {
        //     return response()->json([
        //         "errors" => "No access" // It seems you don't have access to this system. Please contact the system owner for assistance.
        //     ], 401);
        // }

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
        // $request->user()->accesses = $this->userAccesses($user);

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

    private function userAccesses($user) {

        $userAccesses = [];
        $accesses = $this->userSystemAccessesVw->getWhere(['user_id' => $user->id])->result();
        /** Get the user admin accesses from all systems */
        $accesses = $accesses->whereIn('user_type_id', [1, 2, 3, 4, 5, 6, 7, 8]); 

        if(!$accesses->isEmpty()) {
            foreach($accesses as $access) {
                $userAccesses [] = (object) [
                    'user_type_id' => $access->user_type_id,
                    'user_type' => $access->user_type,
                    'system_id' => $access->system_id,
                    'system' => $access->system
                ];
            } 
        }
        return $userAccesses ?? null;
    }
}
