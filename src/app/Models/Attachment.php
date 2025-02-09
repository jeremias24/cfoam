<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Attachment extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'attachments';
    protected $fillable = [
        'reference_id',
        'filename',
        'filesize',
        'filetype',
        'filepath',
        'mime_type',
        'last_modified',
    ];
    protected $casts = [
        'created_datetime' => 'datetime:Y-m-d H:i:s',
        'last_updated_datetime' => 'datetime:Y-m-d H:i:s'
    ];
    public $timestamps = false;

    public $attachments;

    public function attributes()
    {

        $attributes = Arr::pluck(DB::connection('mysql')->select('DESCRIBE ' . $this->table), 'Field');
        
        return $attributes;
        
    }

    public function getWhere($filter)
    {
        
        $this->attachments = $this->select([
                'a.*'
            ])
            ->where($filter)
            ->orderBy('a.id', 'DESC')
            ->get();

        return $this;

    }

    public function getWhereIn($filter)
    {
        
        $this->table = 'mis.attachments AS a';

        $this->attachments = $this->select([
                'a.*'
            ])
            ->whereIn('a.' . $filter->key, $filter->value)
            ->orderBy('a.id', 'DESC')
            ->get();

        return $this;

    }

    public function result()
    {
        return $this->attachments;
    }
}
