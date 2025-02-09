<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AttachmentsResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('reference_id') => $this->reference_id,
            'filename' => $this->filename,
            'filetype' => $this->filetype,
            'filesize' => $this->filesize,
            'filepath' => $this->filepath,
            Str::camel('mime_type') => $this->mime_type,
            Str::camel('last_modified') => $this->last_modified,
            Str::camel('created_by') => $this->created_by,
            Str::camel('created_datetime') => (! is_null($this->created_datetime)) ? Carbon::parse($this->created_datetime)->format('Y-m-d H:i:s') : $this->created_datetime,
            Str::camel('last_updated_by') => $this->last_updated_by,
            Str::camel('last_updated_datetime') => (! is_null($this->last_updated_datetime)) ? Carbon::parse($this->last_updated_datetime)->format('Y-m-d H:i:s') : $this->last_updated_datetime,
        ];
    }
}