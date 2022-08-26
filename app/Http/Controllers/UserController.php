<?php

namespace App\Http\Controllers;

use App\Models\MultiSite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Helper;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check User Permissions
        $this->middleware('RolePermissionCheck:users.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:users.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:users.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:users.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Users', 'View all user list');

        $users = User::with(['role','site'])->get();
        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Users', 'Open create user form');

        $roles = Role::all();
        $multiSites = MultiSite::all();

        return view('users.create', ['roles' => $roles, 'multiSites' => $multiSites]);
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
            'email' => 'required|string|email|max:150|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'site_id' => 'required|exists:sites,id',
            'basic_salary' => 'required',
            'overtime_salary' => 'required'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->site_id = $request->site_id;
        $user->password = Hash::make($request->password);
        $user->basic_salary = number_format($request->basic_salary, 4);
        $user->overtime_salary = number_format($request->overtime_salary, 4);
        $user->save();

        Helper::logSystemActivity('Users', 'Added user successfully');

        // Back to index with success
        return redirect()->route('users.index')->with('custom_success', 'User has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Users', 'View user details id: ' . $id);

        $user = User::with(['role', 'site'])->find($id);
        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Users', 'Edit user form opened id: ' . $id);

        $roles = Role::all();
        $multiSites = MultiSite::all();
        $user = User::with(['role', 'site'])->find($id);
        return view('users.edit', ['user' => $user, 'roles' => $roles, 'multiSites' => $multiSites]);
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
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'site_id' => 'required|exists:sites,id',
            'basic_salary' => 'required',
            'overtime_salary' => 'required'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $user = User::findOrFail($id);

        // Update the item
        $user->name = $request->name;
        $user->basic_salary = number_format($request->basic_salary, 4);
        $user->overtime_salary = number_format($request->overtime_salary, 4);
        $user->role_id = $request->role_id;
        $user->site_id = $request->site_id;
        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        } else {
            $user->password = $user->password;
        }

        $user->save();

        Helper::logSystemActivity('Users', 'Edit user successfull id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'User has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the user by $id
        $user = User::findOrFail($id);

        // Prevent user from self deleting
        if ($user->id == Auth::user()->id) {
            return back()->with('custom_errors', 'You can not delete yourself. Ask super admin to do that.');
        }

        // delete user
        $status = $user->delete();

        if ($status) {
            Helper::logSystemActivity('Users', 'Delete user successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'User has been deleted');
        } else {
            Helper::logSystemActivity('Users', 'Delete user failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'User was not deleted. Something went wrong.');
        }
    }
}
