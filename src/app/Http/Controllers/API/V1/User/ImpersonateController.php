<?php 
namespace App\Http\Controllers\API\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Lab404\Impersonate\Services\ImpersonateManager;

use App\Models\UserVw;
use App\Models\UserSystemActiveAccessesVw;

class ImpersonateController extends Controller
{
    protected $manager;
    protected $userSystemActiveAccessesVw;

    public function __construct(ImpersonateManager $manager, UserSystemActiveAccessesVw $userSystemActiveAccessesVw,)
    {
        $this->manager = $manager;
        $this->userSystemActiveAccessesVw = $userSystemActiveAccessesVw;
    }

    public function index(Request $request)
    {
        $user = UserVw::findOrFail($request->id);
        // Check if the current user is authorized to impersonate
        if ($request->section_id != 18) {
            abort(403, 'Unauthorized');
        }

        auth('web')->loginUsingId($request->id);
        $api_token = $user->createToken('main')->plainTextToken;
        $request->user()->api_token = $api_token;
        $request->user()->accesses = $this->userAccesses($user);

        if(count($request->user()->accesses) == 0) {
            return response()->json([
                "errors" => "No access" // It seems you don't have access to this system. Please contact the system owner for assistance.
            ], 401);
        }

        return response()->json([
            'user' => $request->user()
        ]);
    }

    private function userAccesses($user) {

        $userAccesses = [];
        $accesses = $this->userSystemActiveAccessesVw->getWhere(['user_id' => $user->id])->result();
        /** Get the user admin accesses from all systems */
        $accesses = $accesses->whereIn('user_type_id', [2, 3, 4, 5, 6, 7, 8]); 

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