<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Organization;

use App\Resources\OrganizationResource;

class OrganizationsController extends Controller
{   
    public function __construct(Organization $organization) {
        $this->organization = $organization;
    }

    public function index()
    {
        $organizations = $this->organization->getWhere(['active_flag' => 1])->result();

        return response()->json([
            'organizations' => (! is_null($organizations)) ? OrganizationResource::collection($organizations) : $organizations
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

    public function show(Memo $memo)
    {
        //
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
