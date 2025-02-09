<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class SenderResource extends JsonResource
{
    public function toArray($request) {

        return [
            Str::camel('first_name') => $this->first_name,
            Str::camel('middle_name') => $this->middle_name,
            Str::camel('last_name') => $this->last_name,
            Str::camel('full_name') => $this->full_name,
            'email' => $this->email,
            'position' => $this->position,
            Str::camel('section_id') => $this->section_id,
            Str::camel('department_id') => $this->department_id,
            Str::camel('division_id') => $this->division_id,
        ];
    }
}