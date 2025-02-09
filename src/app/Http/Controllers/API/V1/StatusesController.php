<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Status;
use App\Services\Service;
use App\Resources\StatusesResource;

class StatusesController extends Controller
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
    public function show(Status $status, $option)
    {

        $option = (object) json_decode(urldecode(base64_decode($option)), true);

        $statuses = $this->service
            ->model($status)
            ->query($option)
            ->result();

        return [
            'statuses' => (!is_null($statuses)) ? StatusesResource::collection($statuses) : $statuses
        ];

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        //
    }
}
