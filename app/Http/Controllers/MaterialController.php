<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Helper;

class MaterialController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Material Permissions
        $this->middleware('RolePermissionCheck:materials.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:materials.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:materials.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:materials.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materials = Material::get();
        Helper::logSystemActivity('Material', 'Materials list view');
        return view('materials.index', ['materials' => $materials]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Material', 'Add new form open');
        return view('materials.create');
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
            'name' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $material = new Material;
        $material->name = $request->name;
        $material->save();

        Helper::logSystemActivity('Material', 'Add new Material successfully');

        // Back to index with success
        return redirect()->route('materials.index')->with('custom_success', 'New Material has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Material', 'View material details id: ' . $id);
        $material = Material::find($id);
        return view('materials.show', ['material' => $material]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Material', 'Open Edit form of material id: ' . $id);
        $material = Material::find($id);
        return view('materials.edit', ['material' => $material]);
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
            'name' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $material = Material::findOrFail($id);

        // Update the item
        $material->name = $request->name;
        $material->save();

        Helper::logSystemActivity('Material', 'Edit material successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Material has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the material by $id
        $material = Material::findOrFail($id);

        // delete material
        $status = $material->delete();

        if ($status) {
            Helper::logSystemActivity('Material', 'Delete material successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Material has been deleted');
        } else {
            Helper::logSystemActivity('Material', 'Delete material failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Material was not deleted. Something went wrong.');
        }
    }
}
