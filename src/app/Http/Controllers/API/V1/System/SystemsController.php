<?php

namespace App\Http\Controllers\API\V1\System;

use App\Models\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Resources\SystemResource;

class SystemsController extends Controller
{   
    public function __construct(System $system) {
        $this->system = $system;
    }

    public function index()
    {
        $systems = $this->system->getWhere(['us'])->result();

        return response()->json([
            'systems' => (! is_null($systems)) ? SystemResource::collection($systems) : $systems
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
