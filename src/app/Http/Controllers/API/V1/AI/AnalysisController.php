<?php

namespace App\Http\Controllers\API\V1\AI;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Helpers\RelationHelper;
use App\Services\Service;

// use App\Resources\UserTypeResource;

class AnalysisController extends Controller
{   
    protected $pythonServiceUrl;
    protected $http;
    protected $service;

    public function __construct(Service $service) {
        $this->pythonServiceUrl = config('app.url_python');
        $this->http = Http::asMultipart();
        $this->service = $service;
    }

    public function index($attribute, $value)
    {
    }

    
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        // Retrieve all files from the 'images' input.
        $images = $request->images; // Assuming images[] are provided in the request payload

        // Process files via your service (assuming this returns an array with file details)
        $service = $this->service->uploadFile($request->images);

        $client = new Client();

      
        /** Dont remove this is for connection testing */
        // try {
        //     $response = Http::timeout(30)->get($this->pythonServiceUrl . '/get');

        //     if ($response->successful()) {
        //         return $response->json();
        //     } else {
        //         Log::error('Prediction service error: ' . $response->body());
        //         return response()->json(['error' => 'Prediction service error: ' . $response->status()], $response->status());
        //     }
        // } catch (\Exception $e) {
        //     Log::error('Prediction service exception: ' . $e->getMessage());
        //     return response()->json(['error' => 'Prediction service unavailable'], 503);
        // }




        $multipart = [];
        foreach ($service as $index => $image) {

            $multipart[] = [
                'name'     => 'files[]', // This should match the key expected by the Python backend.
                'contents' => $image['filepath'], // Use a stream resource for the file contents.
                'filename' => $image['filename'], // The original file name.

                
            ];

           

        }

        // return  $multipart ;


     
        // Ensure that at least one file is being sent
        if (empty($multipart)) {
            return response()->json(['error' => 'No valid files to upload.'], 400);
        }

        try {
           
            $response = $client->post($this->pythonServiceUrl . '/upload', [
                'multipart' => $multipart,
                // Guzzle will automatically set the 'Content-Type' header to 'multipart/form-data'
            ]);
            
            return response()->json(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to connect to the Python service.'], 500);
        }
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
