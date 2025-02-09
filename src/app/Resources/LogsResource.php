<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class LogsResource extends JsonResource
{

    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('user_request_id') => $this->user_request_id,
            Str::camel('approval_id') => $this->approval_id,
            'details' => $this->details,
            'history' => $this->history,
            Str::camel('created_by') => $this->created_by,
            Str::camel('created_datetime') => (!is_null($this->created_datetime)) ? Carbon::parse($this->created_datetime)->format('Y-m-d H:i:s') : $this->created_datetime,
            Str::camel('last_updated_by') => $this->last_updated_by,
            Str::camel('last_updated_datetime') => (!is_null($this->last_updated_datetime)) ? Carbon::parse($this->last_updated_datetime)->format('Y-m-d H:i:s') : $this->last_updated_datetime,
        ];

    }

}