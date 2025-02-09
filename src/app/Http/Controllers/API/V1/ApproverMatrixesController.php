<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\ApproverMatrix;
use App\Services\Service;
use App\Resources\ApproverMatrixesResource;

class ApproverMatrixesController extends Controller
{

    protected $service;
    
    /**
     * Construct models, controller.
     */
    public function __construct(Service $service)
    {

        $this->service = $service;
       
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ApproverMatrix $approverMatrix, $option)
    {

        $option = (object) json_decode(urldecode(base64_decode($option)), true);

        $approverMatrixes = $this->service
            ->model($approverMatrix)
            ->query($option)
            ->result();

        return [
            Str::camel('approver_matrixes') => (!is_null($approverMatrixes)) ? ApproverMatrixesResource::collection($approverMatrixes) : $approverMatrixes
        ];

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApproverMatrix $approverMatrix)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApproverMatrix $approverMatrix)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApproverMatrix $approverMatrix)
    {
        //
    }
}
