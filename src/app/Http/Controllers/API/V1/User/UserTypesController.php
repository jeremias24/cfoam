<?php

namespace App\Http\Controllers\API\V1\User;

use App\Models\UserType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Resources\UserTypeResource;

class UserTypesController extends Controller
{   
    public function __construct(UserType $userType) {
        $this->userType = $userType;
    }

    public function index($attribute, $value)
    {
        $userTypes = $this->userTypes->getWhere([$attribute => $value, 'active_flag' => 1])->result();

        return response()->json([
            'userTypes' => (! is_null($userTypes)) ? UserTypeResource::collection($userTypes) : $userTypes
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(System $system)
    {
        //
    }

    public function edit(System $system)
    {
        //
    }

    public function update(Request $request, System $system)
    {
        //
    }

    public function destroy(System $system)
    {
        //
    }
}
