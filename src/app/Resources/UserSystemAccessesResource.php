<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserSystemAccessesResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('user_id') => $this->user_id,
            Str::camel('employee_id') => $this->employee_id,
            Str::camel('user_type_id') => $this->user_type_id,
            Str::camel('user_type') => $this->user_type,
            Str::camel('system_id') => $this->system_id,
            'system' => $this->system,
            Str::camel('status_id') => $this->status_id,
            Str::camel('created_by') => $this->created_by,
            Str::camel('created_datetime') => $this->created_datetime,
            Str::camel('last_updated_by') => $this->last_updated_by,
            Str::camel('last_updated_datetime') => $this->last_updated_datetime
        ];
    }
}