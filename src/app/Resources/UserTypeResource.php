<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserTypeResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('system_id') => $this->system_id,
            'name' => $this->name,
            Str::camel('available_to_user_type_id') => $this->available_to_user_type_id,
            Str::camel('user_class_id') => $this->user_class_id,
            Str::camel('active_flag') => $this->active_flag
        ];
    }
}