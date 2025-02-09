<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\UserSystemAccesses;

use App\Resources\UserSystemAccessesResource;

class UserSystemAccessesController extends Controller
{
    protected $userSystemAccesses;

    public function __construct(UserSystemAccesses $userSystemAccesses)
    {
        $this->userSystemAccesses = $userSystemAccesses;
    }

    public function index()
    {
        // 
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $userSystemAccesses = new $this->userSystemAccesses;
        
        foreach ($this->userSystemAccesses->attributes() as $attribute) {
            if ($request->has(Str::camel($attribute))) {
                if($attribute != 'id')
                    $userSystemAccesses->{$attribute} = $request->input(Str::camel($attribute)); 
            }
        }

        //$userSystemAccesses->created_by = $request->input(Str::camel('approver_id')) == null ? Auth::user()->id : $request->input(Str::camel('approver_id'));
        $userSystemAccesses->created_datetime = now()->toDateTimeString();
        $response = $userSystemAccesses->save();

        return [
            'status' => $response ? 'Passed' : 'Failed',
            Str::camel('user_system_access') => (!is_null($userSystemAccesses)) ? new UserSystemAccessesResource($userSystemAccesses->refresh()) : $userSystemAccesses
        ];
    }

    public function show(UserSystemAccesses $userSystemAccesses)
    {
        //
    }

    public function edit(UserSystemAccesses $userSystemAccesses)
    {
        //
    }

    public function update(Request $request, UserSystemAccesses $userSystemAccesses)
    {
        //
    }

    public function destroy(UserSystemAccesses $userSystemAccesses)
    {
        //
    }
}
