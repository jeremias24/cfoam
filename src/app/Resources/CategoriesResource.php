<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CategoriesResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            'name' => $this->name,
            Str::camel('is_active') => $this->is_active,
            Str::camel('created_by') => $this->created_by,
            Str::camel('created_at') => $this->created_at,
            Str::camel('updated_by') => $this->updated_by,
            Str::camel('updated_at') => $this->updated_at,

            'products' => $this->products
        ];
    }
}