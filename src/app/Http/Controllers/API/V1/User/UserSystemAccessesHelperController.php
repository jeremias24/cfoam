<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\UserSystemAccesses;

use App\Resources\UserSystemAccessesResource;

use Carbon\Carbon;

class UserSystemAccessesHelperController extends Controller
{
    protected $userSystemAccesses;

    public function __construct(UserSystemAccesses $userSystemAccesses)
    {
        $this->userSystemAccesses = $userSystemAccesses;
    }

    public function updateOrCreate(Request $request)
    {
        $attributes = [
            'user_id' => $request->input(Str::camel('user_id')),
            'system_id' => $request->input(Str::camel('system_id')),
        ];

        $values = [
            'user_type_id' => $request->input(Str::camel('user_type_id')),
            'status_id' => $request->input(Str::camel('status_id')),
            'status_code' => $request->input(Str::camel('status_code')),
            'last_updated_by' => Auth::user()->id,
            'last_updated_datetime' => now()->toDateTimeString(),
        ];

        $data = UserSystemAccesses::updateOrCreate($attributes, $values);
 
        return [
            'status' => $data ? 'Passed' : 'Failed',
            'access' => (!is_null($data)) ? new UserSystemAccessesResource($data) : $data
        ];
    }
}
 
