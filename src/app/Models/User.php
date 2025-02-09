<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'central';
    public $timestamps = false;
    protected $table = 'central.users';
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
    
    public function getDetails() 
    {
        return null;
    }

    protected $fillable = [
        'user_request_id',
        'employee_id',
        'username',
        'password',
        'password_expiration',
        'remember_token',
        'user_class_id',
        'status_id',
        'status_code',
        'created_by',
        'created_datetime',
        'last_updated_by',
        'last_updated_datetime'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'created_datetime' => 'datetime:Y-m-d H:i:s',
        'last_updated_datetime' => 'datetime:Y-m-d H:i:s'
    ];

    public function attributes() {
        $this->table = 'central.users';

        $attributes = Arr::pluck(DB::connection('central')->select('DESCRIBE ' . $this->table), 'Field');
        return $attributes;
    }

    public function getWhere($filter) {
        $this->users = 
            $this->where($filter)
            ->get();
        
        return $this;
    }
    
    public function getAll()
    {
        $this->table = 'central.users AS u';

        $this->users = $this->select([
            'u.*',
            'u.status_id AS status_id',
            'u.status_code AS status_code',
            'st.name AS status',
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN e.cost_center ELSE NULL END AS cost_center'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN ee.email ELSE pi.email END AS email'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN e.position_title ELSE pi.position_title END AS position_title'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN e.section_id ELSE NULL END AS section_id'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN s.name ELSE pi.section END AS section'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN e.department_id ELSE NULL END AS department_id'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN dp.name ELSE pi.department END AS department'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN e.division_id ELSE NULL END AS division_id'),
            DB::raw('CASE WHEN u.employee_id IS NOT NULL THEN dv.name ELSE pi.division END AS division')
            ])
            ->leftJoin('employee_personal_informations AS epi', 'u.employee_id', '=', 'epi.employee_id')
            ->leftJoin('employees AS e', 'u.employee_id', '=', 'e.id')
            ->leftJoin('employee_emails AS ee', 'e.id', '=', 'ee.employee_id')
            ->leftJoin('sections AS s', 'e.section_id', '=', 's.id')
            ->leftJoin('departments AS dp', 'e.department_id', '=', 'dp.id')
            ->leftJoin('divisions AS dv', 'e.division_id', '=', 'dv.id')
            ->leftJoin('user_classes AS uc', 'u.user_class_id', '=', 'uc.id')
            ->leftJoin('dealers AS ds', 'u.dealer_satellite_id', '=', 'ds.id')
            ->leftJoin('statuses AS st', 'u.status_id', '=', 'st.id')
            ->orderBy('u.id', 'ASC')
            ->get();

        return $this;
    }


    public function getWhereIn($filter) {
        $this->table = 'central.users AS u';

        $this->users = $this->select([
                'u.*'
            ])
            ->whereIn('u.' . $filter->key, $filter->value)
            ->orderBy('u.id', 'DESC')
            ->get();

        return $this;
    }

    public function userDetails() {

        $employeeUsers = DB::table('user_employee_details_vw')
            ->select('id', 'employee_id', 'employee_no', 'first_name', 'middle_name', 'last_name', 'suffix_name', 'nick_name', 'full_name', 'username', 'email', 'cost_center', 'position_title', 'user_class_id', 'user_class', 'section_id', 'section', 'department_id', 'department', 'division_id', 'division');

        $portalUsers = DB::table('user_portal_details_vw')
            ->select('id', 'employee_id', 'employee_no', 'first_name', 'middle_name', 'last_name', 'suffix_name', 'nick_name', 'full_name', 'username', 'email', 'cost_center', 'position_title', 'user_class_id', 'user_class', 'section_id', 'section', 'department_id', 'department', 'division_id', 'division');
     
        $result = $employeeUsers->unionAll($portalUsers)->get(); 

        return $result;
    }

    public function result() {
        return collect($this->users);
    }
}
