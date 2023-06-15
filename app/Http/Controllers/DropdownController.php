<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\Employees;

class DropdownController extends Controller
{

    public function fetchDepartment(Request $request)
    {
        $data['department'] = Departments::where("location_id", $request->location_id)
            ->get(["department_id", "department_name", "location_id"]);
        return response()->json($data);
    }

    public function fetchDesignation(Request $request)
    {
        $data['designation'] = Designations::where("department_id", $request->department_id)
            ->get(["designation_id", "designation_name", "department_id"]);
        return response()->json($data);
    }

    public function fetchEmployeeDept(Request $request)
    {
        $data['employees'] = Employees::where("department_id", $request->department_id)
            ->get(["id", "employee_id", "first_name", "last_name", "email", "username", "profile_picture", "department_id"]);
        return response()->json($data);
    }
}
