<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Helper;

class MachineController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Machine Permissions
        $this->middleware('RolePermissionCheck:machines.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:machines.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:machines.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:machines.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $machines = Machine::get();
        Helper::logSystemActivity('Machine', 'Machines list view');
        return view('machines.index', ['machines' => $machines]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Machine', 'Add new form open');
        return view('machines.create');
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
        $machine = new Machine;
        $machine->name = $request->name;
        $machine->code = $request->code;
        $machine->save();

        Helper::logSystemActivity('Machine', 'Add new Machine successfully');

        // Back to index with success
        return redirect()->route('machines.index')->with('custom_success', 'New Machine has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Machine', 'View machine details id: ' . $id);
        $machine = Machine::find($id);
        return view('machines.show', ['machine' => $machine]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Machine', 'Open Edit form of machine id: ' . $id);
        $machine = Machine::find($id);
        return view('machines.edit', ['machine' => $machine]);
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
        $machine = Machine::findOrFail($id);

        // Update the item
        $machine->name = $request->name;
        $machine->code = $request->code;
        $machine->save();

        Helper::logSystemActivity('Machine', 'Edit machine successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Machine has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the machine by $id
        $machine = Machine::findOrFail($id);

        // delete machine
        $status = $machine->delete();

        if ($status) {
            Helper::logSystemActivity('Machine', 'Delete machine successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Machine has been deleted');
        } else {
            Helper::logSystemActivity('Machine', 'Delete machine failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Machine was not deleted. Something went wrong.');
        }
    }
}
