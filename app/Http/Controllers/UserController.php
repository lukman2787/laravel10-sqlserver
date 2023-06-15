<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Companies;
use App\Models\Departments;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::leftJoin('employees as T1', 'users.employee_id', '=', 'T1.id')
            // ->join('office_locations as T3', 'employees.location_id', '=', 'office_locations.location_id')
            // ->join('departments as T2', 'designations.department_id', '=', 'departments.department_id')
            // ->join('contract_type as T4', 'employees.contract_type', '=', 'contract_type.contract_type_id')
            ->select('users.*', 'T1.id as id_employee', 'T1.employee_id', 'T1.profile_picture')
            ->get();
        return view('users.index', [
            'departments' => Departments::where('company_id', '1')->orderBy('department_id', 'asc')->get(),
            'users' => $users,
            'roles' => Role::orderBy('id', 'DESC')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create', [
            'departments' => Departments::where('company_id', '1')->orderBy('department_id', 'asc')->get(),
            'roles' => Role::orderBy('id', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'username' => ['required', 'unique:users', 'alpha_num', 'min:3', 'max:25'],
            'email' => ['required', 'unique:users', 'email'],
            'password' => ['required', 'min:6', 'same:confirm-password'],
            'employee_id' => ['required'],
            'roles' => ['required'],
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        // $input['user_picture'] = $request->user_picture;
        $input['user_picture'] = 'user_default.jpg';

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with(['success' => 'User created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
            'departments' => Departments::where('company_id', '1')->orderBy('department_id', 'asc')->get(),
        ]);
        // return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            // 'username' => ['required', 'unique:users', 'alpha_num', 'min:3', 'max:25'] . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'employee_id' => ['required'],
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
