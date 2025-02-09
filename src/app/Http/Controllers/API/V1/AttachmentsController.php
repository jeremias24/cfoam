<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Resources\AttachmentsResource;

class AttachmentsController extends Controller
{
    protected $attachment;

    /**
     * Construct models, contro
     */
    public function __construct(Attachment $attachment)
    {

        $this->attachment = $attachment;
        
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

        $attachment = new $this->attachment;

        foreach ($this->attachment->attributes() as $attribute) {
            if ($request->has(Str::camel($attribute))) {
                $attachment->{$attribute} = $request->input(Str::camel($attribute));
            }
        }

        $attachment->created_by = 1;
        $attachment->created_datetime = now()->toDateTimeString();
        
        $response = $attachment->save();

        return (object) [
            'status' => $response ? 'Passed' : 'Failed',
            'attachment' => (!is_null($attachment)) ? new AttachmentsResource($attachment->refresh()) : $attachment
        ];

    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        //
    }
}
