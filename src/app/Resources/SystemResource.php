<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SystemResource extends JsonResource
{
    public function toArray($request) {

        return [
            Str::camel('system_id') => $this->system_id,
            Str::camel('owner_section_id') => $this->owner_section_id,
            Str::camel('owner_section') => $this->owner_section,
            Str::camel('user_type_id') => $this->user_type_id,
            Str::camel('user_type') => $this->user_type,
            Str::camel('option_user_type_id') => $this->option_user_type_id,
            Str::camel('system_id') => $this->system_id,
            'system' => $this->system,
            'description' => $this->description,
            'icon' => $this->icon,
            'url' => $this->url
        ];
    }
}