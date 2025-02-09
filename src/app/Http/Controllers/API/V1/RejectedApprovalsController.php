<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\RejectedApproval;
use App\Resources\RejectedApprovalsResource;

class RejectedApprovalsController extends Controller
{
    protected $rejectedApproval;

    /**
     * Construct models, controller.
     */
    public function __construct(RejectedApproval $rejectedApproval)
    {

        $this->rejectedApproval = $rejectedApproval;
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rejectedApproval = new $this->rejectedApproval;

        foreach ($this->rejectedApproval->attributes() as $attribute) {
            if ($request->has(Str::camel($attribute))) {
                $rejectedApproval->{$attribute} = $request->input(Str::camel($attribute));
            }
        }

        $rejectedApproval->created_by = Auth::user()->id;;
        $rejectedApproval->created_datetime = now()->toDateTimeString();
        $response = $rejectedApproval->save();

        return [
            'status' => $response ? 'Passed' : 'Failed',
            Str::camel('rejected_approval') => (!is_null($rejectedApproval)) ? new RejectedApprovalsResource($rejectedApproval->refresh()) : $rejectedApproval
        ];

    }

    /**
     * Display the specified resource.
     */
    public function show(RejectedApproval $rejectedApproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RejectedApproval $rejectedApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RejectedApproval $rejectedApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RejectedApproval $rejectedApproval)
    {
        //
    }
}
