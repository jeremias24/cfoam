<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

use App\Services\Service;

use App\Models\User;

use App\Resources\UserResource;
use App\Resources\UserSystemAccessesResource;

class UsersHelperController extends Controller
{   
    protected $service;

    public function __construct(User $user, Service $service) {
        $this->user = $user;
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->user->userDetails();

        return response()->json([
            'users' => (! is_null($users)) ? UserResource::collection($users) : $users
        ]);
    }

    public function updateAccesses(Request $request)
    {
        try {
            /** Begin a database transaction */
            DB::beginTransaction();
        
            /** Begin store user accesses request */
            if ($request->has('accesses')) {
                foreach ($request->input('accesses') as $access) {
                    $serviceResponse = $this->service
                        ->controller('App\Http\Controllers\API\V1\User\UserSystemAccessesHelperController')
                        ->updateOrCreate(Arr::add($access, Str::camel('user_id'), $request->id))
                        ->result();

                    if ($serviceResponse->status == 'Failed') {
                        throw new \Exception('Saving of access has failed');
                    }
                }
            }

            /** Begin store user accesses request */
            $data = $this->service
                    ->model($this->user)
                    ->query((object) [
                        'show' => 'filter',
                        'filter' => [
                            'id' => $request->id,
                        ],
                    ])
                ->relation(\App\Models\UserSystemAccesses::class, Str::camel('user_system_accesses'), 'user_id', 'id')
                ->result()
                ->first();

            DB::commit();

            return [
                'status' => $serviceResponse->status != 'Failed' ? 'Passed' : 'Failed',
                'user' => (!is_null($data)) ? new Useresource($data) : $data
            ];
        
        } catch(\Exception $e) {

            DB::rollback();

            if($this->isNetworkError($e)) {
                return response()->json(['error' => 'Network error'], 500);
            }

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function isNetworkError($exception)
    {
        return $exception instanceof \Illuminate\Database\QueryException && $exception->getCode() == "HY000";
    }
}
