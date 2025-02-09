<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Signatory;
use App\Services\Service;
use App\Resources\SignatoriesResource;

class SignatoriesController extends Controller
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
    public function show(Signatory $signatory, $option)
    {

        $option = (object) json_decode(urldecode(base64_decode($option)), true);

        $signatories = $this->service
            ->model($signatory)
            ->query($option)
            ->result();
            
        return [
            'signatories' => (!is_null($signatories)) ? new SignatoriesResource($signatories) : $signatories
        ];

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Signatory $signatory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Signatory $signatory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signatory $signatory)
    {
        //
    }
}
