<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ProductsResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            Str::camel('category_id') => $this->category_id,
            Str::camel('is_active') => $this->is_active,
            Str::camel('created_by') => $this->created_by,
            Str::camel('created_at') => (!is_null($this->created_at)) ? Carbon::parse($this->created_at)->format('Y-m-d H:i:s') : $this->created_at,
            Str::camel('updated_by') => $this->updated_by,
            Str::camel('updated_at') => (!is_null($this->updated_at)) ? Carbon::parse($this->updated_at)->format('Y-m-d H:i:s') : $this->updated_at,
            'category' => $this->category,
        ];
    }
}