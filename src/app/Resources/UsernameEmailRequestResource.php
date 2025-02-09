<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UsernameEmailRequestResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email
        ];
    }
}