<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class ChatMessage extends Model
{
    protected $connection = 'mysql';
    protected $table = 'cfoam.chat_messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message'
    ];

    public $timestamps = false;
    public $messages;

    public function attributes()
    {
        $attributes = Arr::pluck(DB::connection('mysql')->select('DESCRIBE ' . $this->table), 'Field');
        
        return $attributes;   
    }

    public function getWhere($filter) {

        $this->messages = $this->where($filter)
            ->orderBy('id')
            ->get();
            
        return $this;
    }

    public function getChatMessages($filter) 
    {  
    

        $this->messages = $this->where(
            function ($query) use ($filter) {
                $query->where('sender_id', $filter['sender_id'])
                ->where('receiver_id', $filter['receiver_id']); 
            })->orWhere(function ($query) use ($filter) {
                $query->where('sender_id', $filter['sender_id'])
                    ->where('receiver_id', $filter['receiver_id']);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('id', 'asc')
            ->get();
    
        return $this;
    }

    public function getAll($filter) 
    {
        
    }

    public function result() {
        return $this->messages;
    }

    public function sender() {
        return $this->belongsTo(UserVw::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(UserVw::class, 'receiver_id');
    }
}
