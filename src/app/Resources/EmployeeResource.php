<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EmployeeResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('encrypted_id') => Crypt::encryptString($this->id),
            Str::camel('employee_no') => $this->employee_no,
            Str::camel('first_name') => $this->first_name,
            Str::camel('middle_name') => $this->middle_name,
            Str::camel('last_name') => $this->last_name,
            Str::camel('full_name') => $this->full_name,
            Str::camel('suffix_name') => $this->suffix_name,
            Str::camel('nick_name') => $this->nick_name,
            'position' => $this->position,
            Str::camel('position_title') => $this->position_title,
            'rank' => Crypt::encryptString($this->rank),
            Str::camel('cost_center') => Crypt::encryptString($this->cost_center),
            Str::camel('shop_cost_center') => Crypt::encryptString($this->shop_cost_center),
            'email' => $this->email,
            Str::camel('phone_number') => $this->phone_number,
            Str::camel('status_id') => $this->status_id,
            Str::camel('division_id') => $this->division_id,
            Str::camel('department_id') => $this->department_id,
            Str::camel('section_id') => $this->section_id,
            'section' => $this->section,
            'department' => $this->department,
            'division' => $this->division,
        ];
    }
}