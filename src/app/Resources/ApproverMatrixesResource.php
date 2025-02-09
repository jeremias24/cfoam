<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ApproverMatrixesResource extends JsonResource
{

    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('approver_id') => $this->approver_id,
            'position' => $this->position,
            Str::camel('position_abbrev') => $this->position_abbrev,
            Str::camel('assessor_id') => $this->assessor_id,
            Str::camel('active_flag') => $this->active_flag,
            Str::camel('created_by') => $this->created_by,
            Str::camel('created_datetime') => (!is_null($this->created_datetime)) ? Carbon::parse($this->created_datetime)->format('Y-m-d H:i:s') : $this->created_datetime,
            Str::camel('last_updated_by') => $this->last_updated_by,
            Str::camel('last_updated_datetime') => (!is_null($this->last_updated_datetime)) ? Carbon::parse($this->last_updated_datetime)->format('Y-m-d H:i:s') : $this->last_updated_datetime,
        ];

    }

}