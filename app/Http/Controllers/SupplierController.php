<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Helper;

class SupplierController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Supplier Permissions
        $this->middleware('RolePermissionCheck:suppliers.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:suppliers.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:suppliers.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:suppliers.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::get();
        Helper::logSystemActivity('Supplier', 'Suppliers list view');
        return view('suppliers.index', ['suppliers' => $suppliers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Supplier', 'Add new form open');
        return view('suppliers.create');
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
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $supplier = new Supplier;
        $supplier->name = $request->name;
        $supplier->save();

        Helper::logSystemActivity('Supplier', 'Add new Supplier successfully');

        // Back to index with success
        return redirect()->route('suppliers.index')->with('custom_success', 'New Supplier has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Supplier', 'View supplier details id: ' . $id);
        $supplier = Supplier::find($id);
        return view('suppliers.show', ['supplier' => $supplier]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Supplier', 'Open Edit form of supplier id: ' . $id);
        $supplier = Supplier::find($id);
        return view('suppliers.edit', ['supplier' => $supplier]);
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
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $supplier = Supplier::findOrFail($id);

        // Update the item
        $supplier->name = $request->name;
        $supplier->save();

        Helper::logSystemActivity('Supplier', 'Edit supplier successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Supplier has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the supplier by $id
        $supplier = Supplier::findOrFail($id);

        // delete supplier
        $status = $supplier->delete();

        if ($status) {
            Helper::logSystemActivity('Supplier', 'Delete supplier successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Supplier has been deleted');
        } else {
            Helper::logSystemActivity('Supplier', 'Delete supplier failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Supplier was not deleted. Something went wrong.');
        }
    }
}
