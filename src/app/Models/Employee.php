<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    protected $table = 'central.employees';

    public $timestamps = false;

    public $employees;

    public function getWhere($filter) {
        $this->employees = 
            $this->where($filter)
            ->get();
        
        return $this;
    }

    public function getAll() 
    {
        $this->employees = $this->leftJoin('employee_personal_informations AS epi', function($join) {
            $join->on('employees.id', '=', 'epi.employee_id');
          })
          ->leftJoin('divisions AS dv', 'employees.division_id', '=', 'dv.id')
          ->leftJoin('departments AS dp', 'employees.department_id', '=', 'dp.id')
          ->leftJoin('sections AS s', 'employees.section_id', '=', 's.id')
          ->leftJoin('employee_emails AS ee', 'employees.id', '=', 'ee.employee_id')
          ->whereIn('employees.status_id', [1, 2])
          ->select([
            'employees.id',
            'employees.employee_no',
            'epi.first_name',
            'epi.middle_name',
            'epi.last_name',
            \DB::raw('CONCAT_WS(" ",epi.first_name, IFNULL(epi.middle_name, ""), epi.last_name) AS full_name'),
            'epi.suffix_name',
            'epi.nick_name',
            'employees.position',
            'employees.position_title',
            'employees.rank',
            'employees.cost_center',
            'employees.shop_cost_center',
            'ee.email',
            'epi.phone_number',
            'employees.status_id',
            'employees.division_id',
            'employees.department_id',
            'employees.section_id',
            's.name AS section',
            'dp.name AS department',
            'dv.name AS division'
            ])
        ->get();

        return $this;
    }
    
    public function result() {
        return collect($this->employees);
    }
}
