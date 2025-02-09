<?php

namespace App\Http\Controllers\API\V1\AI;

// use App\Models\UserType;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// use App\Resources\UserTypeResource;

class KerasController extends Controller
{   
    // public function __construct(UserType $userType) {
    //     $this->userType = $userType;
    // }

    public function index($attribute, $value)
    {
        $userTypes = $this->userTypes->getWhere([$attribute => $value, 'active_flag' => 1])->result();

        return response()->json([
            'userTypes' => (! is_null($userTypes)) ? UserTypeResource::collection($userTypes) : $userTypes
        ]);
    }

    public function predict(Request $request)
    {
       // return ['test' => $request->all()];

        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

       // return ['test' =>  $validatedData];

        $response = Http::post('http://cfoam-jupyter:5000/predict', [
            'input' => [
                $validatedData['price'],
                $validatedData['stock_quantity'],
                $validatedData['category_id'],
                $validatedData['is_active'],
            ]
        ]);

       

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Prediction service unavailable'], 503);
        }
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
