<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;

use App\Resources\CategoriesResource;

class CategoriesController extends Controller
{   
    public function __construct(Category $category) {
        $this->category = $category;
    }

    public function index()
    {
        $categories = $this->category->getWhere(['is_active' => 1])->result();

        return [
            'categories' => (! is_null($categories)) ? CategoriesResource::collection($categories) : $categories
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
