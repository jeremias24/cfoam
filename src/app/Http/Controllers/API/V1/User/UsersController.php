<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\UserVw;
use App\Models\UserRequest;

use App\Services\Service;

use App\Resources\UserResource;

class UsersController extends Controller
{   
    public function __construct(User $user, UserVw $userVw, UserRequest $userRequest, Service $service) {
        $this->user = $user;
        $this->userVw = $userVw;
        $this->userRequest = $userRequest;
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->user->userDetails();

        return response()->json([
            'users' => (! is_null($users)) ? UserResource::collection($users) : $users
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $option = null)
    {
        $user = new $this->user;
        
        foreach ($this->user->attributes() as $attribute) {
            if ($request->has(Str::camel($attribute))) {
                $user->{$attribute} = $request->input(Str::camel($attribute)); 
            }
        }

        // $personalInfo->created_by = $request->input(Str::camel('approver_id')) == null ? Auth::user()->id : $request->input(Str::camel('approver_id'));
        $user->created_datetime = now()->toDateTimeString();
        $response = $user->save();

        return [
            'status' => $response ? 'Passed' : 'Failed',
            'user' => (!is_null($user)) ? new UserResource($user->refresh()) : $user
        ];
    }

    public function show(UserVw $userVw, $option)
    {
        $option = (object) json_decode(urldecode(base64_decode($option)), true);

        $users = $this->service
            ->model($userVw)
            ->query($option)
            ->relation(\App\Models\UserSystemAccesses::class, Str::camel('user_system_accesses'), 'user_id', 'id')
            ->result();

        return [
           'users' => (! is_null($users)) ? UserResource::collection($users) : $users
        ];
    }

    public function edit(Memo $memo)
    {
        //
    }

    public function update(Request $request, Memo $memo)
    {
        //
    }

    public function destroy(Memo $memo)
    {
        //
    }
}
