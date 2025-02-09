<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SystemHelperResource extends JsonResource
{
    public function toArray($request) {

        return [
            Str::camel('system_id') => $this->system->system_id,
            Str::camel('owner_section_id') => $this->system->owner_section_id,
            Str::camel('owner_section') => $this->system->owner_section,
            Str::camel('user_type_id') => $this->system->user_type_id,
            Str::camel('user_type') => $this->system->user_type,
            Str::camel('option_user_type_id') => $this->system->option_user_type_id,
            'options' => $this->options,
            Str::camel('system_id') => $this->system->system_id,
            'system' => $this->system->system,
            'description' => $this->system->description,
            'icon' => $this->system->icon,
            'url' => $this->system->url
        ];
    }
}