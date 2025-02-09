<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class SignatoriesResource extends JsonResource
{

    public function toArray($request) {

        $signatories = $this->map(function ($value) {
            return [
                'id' => ((object) $value)->id,
                'signatory' => ((object) $value)->signatory,
                'position' => ((object) $value)->position,
                Str::camel('position_suffix') => ((object) $value)->position_suffix,
            ];
        });
        
        return $signatories;

    }

}