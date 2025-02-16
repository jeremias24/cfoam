<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CarPart extends Model
{
    protected $connection = 'mysql';
    protected $table = 'cfoam.car_parts';

    public $timestamps = false;
    public $carParts;

    public function getWhere($filter) {
        $this->carParts = 
            $this->where($filter)
            ->orderBy('name')
            ->get();
        
        return $this;
    }

    public function getAll() 
    {
        
    }

    public function result() {
        return $this->carParts;
    }
}
