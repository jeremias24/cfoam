<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'central.logs';
    protected $fillable = [
        'user_request_id',
        'approval_id',
        'details',
        'history',
    ];
    protected $casts = [
        'created_datetime' => 'datetime:Y-m-d H:i:s',
        'last_updated_datetime' => 'datetime:Y-m-d H:i:s'
    ];
    public $timestamps = false;

    public $logs;

    public function attributes()
    {
        $this->table = 'centralized.logs';

        $attributes = Arr::pluck(DB::connection('mysql')->select('DESCRIBE ' . $this->table), 'Field');
        
        return $attributes;
    }
}
