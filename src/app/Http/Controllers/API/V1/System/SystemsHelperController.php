<?php

namespace App\Http\Controllers\API\V1\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;

use App\Models\System;
use App\Models\UserSystemActiveAccessesVw;
use App\Models\UserType;

use App\Resources\SystemHelperResource;

class SystemsHelperController extends Controller
{   
    public function __construct(System $system, UserSystemActiveAccessesVw $userSystemActiveAccessesVw, UserType $userType) {
        $this->system = $system;
        $this->userSystemActiveAccessesVw = $userSystemActiveAccessesVw;
        $this->userType = $userType;
    }

    public function getSystemActiveAccesses(Request $request)
    {
        // return response()->json([
        //     'systems' => Session::get('user')
        // ]);

        $systems = $this->userSystemActiveAccessesVw->getWhere(['user_id' => $request->user()->id])->result();

        $systemsWithUserTypes = [];

        foreach($systems as $system) {

            $types = $this->userType->whereIn('id', json_decode($system->option_user_type_id, TRUE))->get();
            $userTypes = [];

            foreach($types as $type) {
                $userTypes [] = [
                    'label' => $type->name,
                    'value' => $type->id,
                    'userClassId' => $type->user_class_id,
                ];
            }

            $systemsWithUserTypes [] = (object) [
                'system' => $system,
                'options' => $userTypes
            ];
        }

        return response()->json([
            'systems' => (! is_null($systemsWithUserTypes)) ? SystemHelperResource::collection($systemsWithUserTypes) : $systemsWithUserTypes
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
