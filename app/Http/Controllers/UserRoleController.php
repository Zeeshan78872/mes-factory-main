<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Helper;

class UserRoleController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check User Permissions
        $this->middleware('RolePermissionCheck:roles.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:roles.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:roles.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:roles.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('User Roles', 'View all user roles list');

        $roles = Role::all();
        return view('user-roles.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('User Roles', 'Add user role form opened');

        $permissions = Helper::getPermissionsList();
        return view('user-roles.create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }
        $permissionsStr = '{"permissions": ';
        $permissionsStr .= json_encode($request->permissions);
        $permissionsStr .= '}';
        
        // Store the item
        $role = new Role;
        $role->name = $request->name;
        $role->permissions = $permissionsStr;
        $role->save();

        Helper::logSystemActivity('User Roles', 'User Role created successfully');

        // Back to index with success
        return redirect()->route('roles.index')->with('custom_success', 'Role has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('User Roles', 'View User Role details id: ' . $id);

        $permissions = Helper::getPermissionsList();
        $role = Role::find($id);
        return view('user-roles.show', ['role' => $role, 'permissions' => $permissions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('User Roles', 'Edit User Role form opened id: ' . $id);

        $permissions = Helper::getPermissionsList();
        $role = Role::find($id);
        return view('user-roles.edit', ['role' => $role, 'permissions' => $permissions]);
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
        // Validations
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $role = Role::findOrFail($id);

        $permissionsStr = '{"permissions": ';
        $permissionsStr .= json_encode($request->permissions);
        $permissionsStr .= '}';
        // Update the item
        $role->name = $request->name;
        $role->permissions = $permissionsStr;
        $role->save();

        Helper::logSystemActivity('User Roles', 'Update User Role successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Role has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the role by $id
        $role = Role::findOrFail($id);

        // delete role
        $status = $role->delete();

        if ($status) {
            Helper::logSystemActivity('User Roles', 'Delete User Role successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Role has been deleted');
        } else {
            Helper::logSystemActivity('User Roles', 'Delete User Role failed');

            // If no success
            return back()->with('custom_errors', 'Role was not deleted. Something went wrong.');
        }
    }
}
