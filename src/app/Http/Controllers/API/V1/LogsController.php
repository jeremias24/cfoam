<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Resources\LogsResource;

class LogsController extends Controller
{
    protected $log;

    /**
     * Construct models, contro
     */
    public function __construct(Log $log)
    {

        $this->log = $log;
        
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

        $log = new $this->log;

        foreach ($this->log->attributes() as $attribute) {
            if ($request->has(Str::camel($attribute))) {
                $log->{$attribute} = $request->input(Str::camel($attribute));
            }
        }

        // $log->created_by = 1;
        $log->created_datetime = now()->toDateTimeString();
        $response = $log->save();

        return [
            'status' => $response ? 'Passed' : 'Failed',
            Str::camel('log') => (!is_null($log)) ? new LogsResource($log->refresh()) : $log
        ];

    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Log $log)
    {
        //
    }
}
