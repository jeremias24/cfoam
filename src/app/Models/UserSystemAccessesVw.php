<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class UserSystemAccessesVw extends Model
{
    protected $connection = 'central';
    protected $table = 'central.user_system_accesses_vw';
    protected $primaryKey = 'user_id';

    public $accesses;

    public function getWhere($filter) {
        $this->accesses = 
            $this->where($filter)
            ->orderby('owner_section_id', 'ASC')
            ->get();
        
        return $this;
    }

    public function result() {
        return collect($this->accesses);
    }
}
