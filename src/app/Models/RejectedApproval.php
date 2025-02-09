<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RejectedApproval extends Model
{

    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'rejected_approvals';
    protected $fillable = [
        'approval_id',
        'reference_id',
        'reason',
        'notification_email_sent_flag',
        'notification_email_sent_datetime',
    ];
    protected $casts = [
        'notification_email_sent_datetime' => 'datetime:Y-m-d H:i:s',
        'created_datetime' => 'datetime:Y-m-d H:i:s',
        'last_updated_datetime' => 'datetime:Y-m-d H:i:s'
    ];
    public $timestamps = false;

    public $rejectedApprovals;

    public function attributes()
    {

        $attributes = Arr::pluck(DB::connection('mysql')->select('DESCRIBE ' . $this->table), 'Field');
        
        return $attributes;
        
    }

    public function getWhere($filter)
    {
        
        $this->rejectedApprovals = $this->where($filter)
            ->orderBy('id', 'DESC')
            ->get();

        return $this;

    }

    public function getWhereIn($filter)
    {

        $this->rejectedApprovals = $this->whereIn($filter->key, $filter->value)
            ->orderBy('id', 'DESC')
            ->get();

        return $this;

    }

    public function result()
    {

        return $this->rejectedApprovals;

    }
    
}
