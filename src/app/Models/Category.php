<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $connection = 'mysql';
    protected $table = 'cfoam.categories';

    public $timestamps = false;
    public $categories;

    public function getWhere($filter) {
        $this->categories = 
            $this->where($filter)
            ->with('products')
            ->orderBy('name')
            ->get();
        
        return $this;
    }

    public function getAll() 
    {
        
    }

    public function result() {
        return $this->categories;
    }

    public function products() 
    {
        return $this->hasMany('App\Models\Product');
    }
}
