<?php

namespace App\Http\Controllers\API\V1\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PredictionController extends Controller
{   
    protected $pythonServiceUrl;

    public function __construct()
    {
        $this->pythonServiceUrl = env('APP_URL_PYTHON');
    }

    public function predict(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'size' => 'required|integer',
            'category_id' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);
       
        try {
            $response = Http::timeout(30)->post($this->pythonServiceUrl . '/predict', [
                'product_id' => $validatedData['product_id'],  
                'size' => $validatedData['size'],
                'category_id' => $validatedData['category_id'],
                'is_active' => $validatedData['is_active'],
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Prediction service error: ' . $response->body());
                return response()->json(['error' => 'Prediction service error: ' . $response->status()], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Prediction service exception: ' . $e->getMessage());
            return response()->json(['error' => 'Prediction service unavailable'], 503);
        }
    } 

    public function train()
    {
        try {
            $response = Http::timeout(300)->post($this->pythonServiceUrl . '/train');

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Training service error: ' . $response->body());
                return response()->json(['error' => 'Training service error: ' . $response->status()], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Training service exception: ' . $e->getMessage());
            return response()->json(['error' => 'Training service unavailable'], 503);
        }
    }
}