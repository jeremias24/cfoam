<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class UserEmployeeDetailsVw extends Model
{
    protected $table = 'user_employee_details_vw';
    protected $primaryKey = 'id';

    public $users;

    public function getWhere($filter) {
        $this->users = 
            $this->where($filter)
            ->get();
        
        return $this;
    }

    public function result() {
        return collect($this->users);
    }
}
