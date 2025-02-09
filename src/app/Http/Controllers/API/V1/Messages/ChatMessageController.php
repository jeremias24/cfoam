<?php

namespace App\Http\Controllers\API\V1\Messages;

use App\Events\MessageEvent;
use App\Events\ProductEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

use App\Models\ChatMessage;
use App\Models\UserVw;

use App\Services\Service;

use App\Resources\ChatMessageResource;

class ChatMessageController extends Controller
{   
    protected $pythonServiceUrl;
    protected $chatbotName;

    public function __construct(UserVw $userVw, ChatMessage $chatMessage, Service $service) {
        $this->userVw = $userVw;
        $this->chatMessage = $chatMessage;
        $this->service = $service;
        $this->pythonServiceUrl = config('app.url_python');
        $this->chatbotName = config('app.chatbot_name');
    }

    public function getChatMessages($value)
    {
        $chatMessages = $this->chatMessage->getChatMessages(['receiver_id' => Auth::user()->id, 'sender_id' => $value])->result();

        return response()->json([
            Str::camel('chat_messages') => (! is_null($chatMessages)) ? ChatMessageResource::collection($chatMessages) : $chatMessages
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $option = null)
    {

        $chatMessage = new $this->chatMessage;

        /* try {
           
            $response = Http::timeout(100)->get($this->pythonServiceUrl . '/get', [
                'msg' => $request->message
            ]);
    
            if ($response->successful()) {

                $data = $response->json();

                foreach ($this->chatMessage->attributes() as $attribute) {
                    if ($request->has(Str::camel($attribute))) {
                        $chatMessage->{$attribute} = $request->input(Str::camel($attribute)); 
                    }
                }

                $chatMessage->created_at = now()->toDateTimeString();
                $response = $chatMessage->save();

                $result = (!is_null($chatMessage)) ? new ChatMessageResource($chatMessage->refresh()) : $chatMessage;

                broadcast(new MessageEvent([
                    'senderId' => null, // Indicating it's from the chatbot
                    'message' => $data['response'] ?? 'No response from service',
                    'firstName' => $this->chatbotName,
                ])); 
    
                return response()->json([
                    'chat' => $data['response'] ?? 'No response from service',
                    'status' => $response ? 'Passed' : 'Failed',
                    Str::camel('chat_message') => $result
                ]);
            } else {
                
                Log::error('Inquiry service error: ' . $response->body());
                return response()->json([
                    'error' => 'Inquiry service error: ' . $response->status(),
                ], $response->status());
            }
        } catch (\Exception $e) {
           
            Log::error('Inquiry service exception: ' . $e->getMessage());
            return response()->json([
                'error' => 'Inquiry service unavailable',
            ], 503);
        } */
        
        foreach ($this->chatMessage->attributes() as $attribute) {
            if ($request->has(Str::camel($attribute))) {
                $chatMessage->{$attribute} = $request->input(Str::camel($attribute)); 
            }
        }

        $chatMessage->created_at = now()->toDateTimeString();
        $response = $chatMessage->save();

        $result = (!is_null($chatMessage)) ? new ChatMessageResource($chatMessage->refresh()) : $chatMessage;

        broadcast(new MessageEvent([
            'senderId' => null, // Indicating it's from the chatbot
            'message' => $request->message, // $data['response'] ?? 'No response from service',
            'firstName' => $this->chatbotName,
        ])); 

        return [
            'status' => $response ? 'Passed' : 'Failed',
            Str::camel('chat_message') => $result
        ];
    }

    public function show(UserVw $userVw, $option)
    {
        $option = (object) json_decode(urldecode(base64_decode($option)), true);

        $users = $this->service
            ->model($userVw)
            ->query($option)
            ->relation(\App\Models\UserSystemAccesses::class, Str::camel('user_system_accesses'), 'user_id', 'id')
            ->result();

        return [
           'users' => (! is_null($users)) ? UserResource::collection($users) : $users
        ];
    }

    public function edit(Memo $memo)
    {
        //
    }

    public function update(Request $request, Memo $memo)
    {
        //
    }

    public function destroy(Memo $memo)
    {
        //
    }
}
