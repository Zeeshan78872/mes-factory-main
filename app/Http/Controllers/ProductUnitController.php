<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Helper;

class ProductUnitController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Product Permissions
        $this->middleware('RolePermissionCheck:product-units.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:product-units.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:product-units.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:product-units.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Product Units', 'View all units list');

        $units = ProductUnit::get();
        return view('product-units.index', ['units' => $units]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Product Units', 'Open create product unit form');

        return view('product-units.create');
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
            'unit_name' => 'required|string|max:255',
            'unit_code' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $unit = new ProductUnit;
        $unit->name = $request->unit_name;
        $unit->code = $request->unit_code;
        $unit->save();

        Helper::logSystemActivity('Product Units', 'Added product unit successfully');

        // Back to index with success
        return redirect()->route('product.units.index')->with('custom_success', 'Product unit has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Product Units', 'View product unit details id: ' . $id);

        $unit = ProductUnit::find($id);
        return view('product-units.show', ['unit' => $unit]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Product Units', 'Edit product unit form opened id: ' . $id);

        $unit = ProductUnit::find($id);
        return view('product-units.edit', ['unit' => $unit]);
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
            'unit_name' => 'required|string|max:255',
            'unit_code' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $unit = ProductUnit::findOrFail($id);

        // Update the item
        $unit->name = $request->unit_name;
        $unit->code = $request->unit_code;
        $unit->save();

        Helper::logSystemActivity('Product Units', 'Edit product unit successfull id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Product unit has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the item by $id
        $unit = ProductUnit::findOrFail($id);
        $status = $unit->delete();

        if ($status) {
            Helper::logSystemActivity('Product Units', 'Delete product unit successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'Product unit has been deleted');
        } else {
            Helper::logSystemActivity('Product Units', 'Delete product unit failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Product unit was not deleted. Something went wrong.');
        }
    }
}
