<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

use App\Models\CarPart;

use App\Resources\CarPartsResource;

class CarPartsController extends Controller
{   
    public function __construct(CarPart $carPart) {
        $this->carPart = $carPart;
    }

    public function index()
    {
        $carParts = $this->carPart->getWhere(['is_active' => 1])->result();

        return [
            Str::camel('car_parts') => (! is_null($carParts)) ? CarPartsResource::collection($carParts) : $carParts
        ];
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, Category $category)
    {
        //
    }

    public function destroy(Category $category)
    {
        //
    }
}
