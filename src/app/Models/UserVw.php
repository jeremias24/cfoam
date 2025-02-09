<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Lab404\Impersonate\Models\Impersonate;

class UserVw extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Impersonate;

    protected $connection = 'central';
    public $timestamps = false;
    protected $table = 'central.users_vw';
    public $users;

    public function username()
    {
        return 'username';
    }

    public function getApiToken()
    {
        return null;
    }

    public function getAccesses() 
    {
        return null;
    }

    protected $fillable = [
        'id',
        'username',
        'email',
        'status_id',
        'password',
        'created_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function attributes() {
        $attributes = Arr::pluck(DB::connection('central')->select('DESCRIBE ' . $this->table), 'Field');
        return $attributes;
    }

    public function getWhere($filter) {

        $this->table = 'central.users_vw AS u';

        $this->users = $this->select([
            'u.*',  'u.password AS passkey'
            ])->where($filter)
            ->get();
        
        return $this;
    }
    
    public function getAll()
    {
        $this->table = 'central.users_vw AS u';

        $this->users = $this->select([
            'u.*',
            ])
            ->orderBy('u.id', 'ASC')
            //->whereNotIn('ur.status_code', ['APRVD'])
            ->get();

        return $this;
    }

    public function getWhereIn($filter) {
        $this->table = 'central.users_vw AS u';

        $this->users = $this->select([
                'u.*'
            ])
            ->whereIn('u.' . $filter->key, $filter->value)
            ->orderBy('u.id', 'DESC')
            ->get();

        return $this;
    }

    public function result() {
        return collect($this->users);
    }
    
}
