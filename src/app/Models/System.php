<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class System extends Model
{
    protected $primaryKey = 'id';

    public $systems;

    public function getWhere($filter) {
        $this->systems = 
            $this->where($systems)
            ->get();
        
        return $this;
    }

    public function result() {
        return collect($this->systems);
    }
}
