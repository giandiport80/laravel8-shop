<?php

namespace App\Http\Controllers\Admin;

use App\Authorizable;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        parent::__construct();

        $this->data['currentAdminMenu'] = 'role-user';
        $this->data['currentAdminSubMenu'] = 'user';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['users'] = User::where('first_name', '!=', 'Administrator')->latest()->paginate(10);

        return view('admin.users.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['roles'] = Role::pluck('name', 'id');

        return view('admin.users.create', $this->data);
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
            'name' => 'bail|required|min:2', // .. 1
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'roles' => 'required|min:1'
        ]);

        $request->merge(['password' => bcrypt($request->input('password'))]);

        DB::transaction(function () use ($request) {
            if($user = User::create($request->except('roles', 'permissions'))){
                $this->syncPermissions($request, $user);
                session()->flash('success', 'User has been created!');
            }else{
                session()->flash('error', 'Unable to create user!');
            }

        });

        return redirect()->route('users.index');
    }

    // ! method private
    // ! ============================================================================

    /**
     * Sync roles and permissions
     *
     * @param Request $request
     * @param $user
     * @return string
     */
    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('roles', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if (!$user->hasAllRoles($roles)) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);

        return $user;
    }

    // ! ============================================================================

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->data['user'] = $user;
        $this->data['roles'] = Role::pluck('name', 'id');
        $this->data['permissions'] = Permission::all('name', 'id');

        return view('admin.users.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required|min:1'
        ]);

        // Get the user
        $user = User::findOrFail($id);

        DB::transaction(function () use ($request, $user) {
            // Update user
            $user->fill($request->except('roles', 'permissions', 'password'));

            // check for password change
            if ($request->get('password')) {
                $user->password = bcrypt($request->get('password'));
            }

            $this->syncPermissions($request, $user);

            $user->save();

            session()->flash('success', 'User has been saved');
        });

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('Admin')) {
            session()->flash('error', 'Unable to remove an Admin user');
            return redirect('admin/users');
        }

        if ($user->delete()) {
            session()->flash('success', 'User has been deleted');
        }
        return redirect()->route('users.index');
    }
}










// h: DOKUMENTASI

// p: clue 1
// bail
// menghentikan validasi setelah validasi pertama gagal (yg ditambahkan bail)
