<?php

namespace App\Http\Controllers\API\V1\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;

use App\Resources\EmployeeResource;

class EmployeesController extends Controller
{
    public function __construct(Employee $employee) {
        $this->employee = $employee;
    }

    public function index()
    {
        $employees = $this->employee->getAll()->result();

        return response()->json([
            'employees' => (! is_null($employees)) ? EmployeeResource::collection($employees) : $employees
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Memo $memo)
    {
        //
    }

    public function edit(Memo $memo)
    {
        //
    }

    public function update(Request $request, Memo $memo)
    {
        //
    }

    public function destroy(Memo $memo)
    {
        //
    }
}
