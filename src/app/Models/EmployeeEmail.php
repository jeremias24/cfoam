<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class EmployeeEmail extends Model
{
    protected $primaryKey = 'id';

    public $email;

    public function getWhere($email) 
    {
        $this->email = 
            $this->where($email)
            ->get();
        
        return $this;
    }

    public function result() 
    {
        return collect($this->email);
    }
}
