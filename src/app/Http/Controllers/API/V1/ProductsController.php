<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;

use App\Resources\ProductsResource;

class ProductsController extends Controller
{
    protected $personalInfo;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        $products = $this->product->getWhere(['is_active' => 1])->result();

        // clock("test");
        // clock()->info($products);

        return [
            'products' => (! is_null($products)) ? ProductsResource::collection($products) : $products
        ];
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $personalInfo = new $this->personalInfo;
        
        foreach ($this->personalInfo->attributes() as $attribute) {
            if ($request->has(Str::camel($attribute))) {
                if($attribute != 'id') 
                    $personalInfo->{$attribute} = $request->input(Str::camel($attribute)); 
            }
        }

        //$personalInfo->created_by = $request->input(Str::camel('approver_id')) == null ? Auth::user()->id : $request->input(Str::camel('approver_id'));
        $personalInfo->created_datetime = now()->toDateTimeString();
        $response = $personalInfo->save();

        return [
            'status' => $response ? 'Passed' : 'Failed',
            Str::camel('personal_info') => (!is_null($personalInfo)) ? new PortalPersonalInformationResource($personalInfo->refresh()) : $personalInfo
        ];
    }

    public function show(PersonalInfo $personalInfo)
    {
        //
    }

    public function edit(PersonalInfo $personalInfo)
    {
        //
    }

    public function update(Request $request, PersonalInfo $personalInfo)
    {
        //
    }

    public function destroy(PersonalInfo $personalInfo)
    {
        //
    }
}
