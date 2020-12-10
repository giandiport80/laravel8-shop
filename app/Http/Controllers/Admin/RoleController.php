<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'role-user';
        $this->data['currentAdminSubMenu'] = 'role';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        $this->data['roles'] = $roles;
        $this->data['permissions'] = $permissions;

        return view('admin.roles.index', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles'
        ]);

        if(Role::create($request->only('name'))){
            session()->flash('success', 'New Role added!');
        }

        return redirect()->route('roles.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        session()->flash('success', $role->name . ' Permissions has been updated!');

        if($role->name == 'Admin'){
            $role->syncPermissions(Permission::all());

            return redirect()->route('roles.index');
        }

        $permissions = $request->input('permissions', []); // .. 1

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index');
    }
}










// h: DOKUMENTASI

// argument kedua kita isi array []
// yang berarti nilai default jika tidak ada input yg dimasukkan

