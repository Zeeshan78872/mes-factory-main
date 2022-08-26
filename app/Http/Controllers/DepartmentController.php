<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Helper;

class DepartmentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Department Permissions
        $this->middleware('RolePermissionCheck:departments.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:departments.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:departments.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:departments.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::get();
        Helper::logSystemActivity('Department', 'Departments list view');
        return view('departments.index', ['departments' => $departments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Department', 'Add new form open');
        return view('departments.create');
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
            'code' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $department = new Department;
        $department->name = $request->name;
        $department->code = $request->code;
        $department->save();

        Helper::logSystemActivity('Department', 'Add new Department successfully');

        // Back to index with success
        return redirect()->route('departments.index')->with('custom_success', 'New Department has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Department', 'View department details id: ' . $id);
        $department = Department::find($id);
        return view('departments.show', ['department' => $department]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Department', 'Open Edit form of department id: ' . $id);
        $department = Department::find($id);
        return view('departments.edit', ['department' => $department]);
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
            'code' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $department = Department::findOrFail($id);

        // Update the item
        $department->name = $request->name;
        $department->code = $request->code;
        $department->save();

        Helper::logSystemActivity('Department', 'Edit department successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Department has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the department by $id
        $department = Department::findOrFail($id);

        if($id < 13) {
            return back()->with('custom_errors', 'Department can not be deleted. it is hard coded in the system.');
        }

        // delete department
        $status = $department->delete();

        if ($status) {
            Helper::logSystemActivity('Department', 'Delete department successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Department has been deleted');
        } else {
            Helper::logSystemActivity('Department', 'Delete department failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Department was not deleted. Something went wrong.');
        }
    }
}
