<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('employee_id') => $this->employee_id,
            Str::camel('employee_no') => $this->employee_no,
            Str::camel('customer_id') => $this->customer_id,
            Str::camel('first_name') => $this->first_name,
            Str::camel('full_name') => $this->full_name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            Str::camel('cost_center') => $this->cost_center,
            Str::camel('position_title') => $this->position_title,
            Str::camel('user_class_id') => $this->user_class_id,
            Str::camel('user_class') => $this->user_class,
            Str::camel('section_id') => $this->section_id,
            'section' => $this->section,
            Str::camel('department_id') => $this->department_id,
            'department' => $this->department,
            Str::camel('division_id') => $this->division_id,
            'division' => $this->division,
            'accesses' =>  (!is_null($this->userSystemAccesses)) ? userSystemAccessesResource::collection($this->userSystemAccesses) : $this->userSystemAccesses,
        ];
    }
}