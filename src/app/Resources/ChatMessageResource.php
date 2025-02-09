<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

use App\Resources\SenderResource;
use App\Resources\ReceiverResource;

class ChatMessageResource extends JsonResource
{
    public function toArray($request) {

        return [
            'id' => $this->id,
            Str::camel('receiver_id') => $this->receiver_id,
            Str::camel('sender_id') => $this->sender_id,
            Str::camel('created_at') => $this->created_at,
            'message' => $this->message,
            'sender' => new SenderResource($this->sender),
            'receiver' => new ReceiverResource($this->receiver)
        ];
    }
}