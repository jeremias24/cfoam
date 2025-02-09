<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Organization extends Model
{
    protected $table = 'central.organizations';
    public $timestamps = false;
    public $organizations;

    public function getWhere($filter) {
        $this->organizations = 
            $this->where($filter)
            ->get();
        
        return $this;
    }

    public function result() {
        return collect($this->organizations);
    }
}
