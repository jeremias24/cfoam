<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $connection = 'mysql';
    protected $table = 'cfoam.products';

    public $timestamps = false;
    public $products;

    public function getWhere($filter) {
        $this->products = 
            $this->where($filter)
            ->with('category')
            ->orderBy('name')
            ->get();
        
        return $this;
    }

    public function getAll() 
    {
        
    }

    public function category() 
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function result() {
        return  collect($this->products);
    }
}
