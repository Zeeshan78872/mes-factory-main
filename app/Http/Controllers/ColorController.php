<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Helper;

class ColorController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Color Permissions
        $this->middleware('RolePermissionCheck:colors.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:colors.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:colors.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:colors.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = Color::get();
        Helper::logSystemActivity('Color', 'Colors list view');
        return view('colors.index', ['colors' => $colors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Color', 'Add new form open');
        return view('colors.create');
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
        $color = new Color;
        $color->name = $request->name;
        $color->code = $request->code;
        $color->save();

        Helper::logSystemActivity('Color', 'Add new Color successfully');

        // Back to index with success
        return redirect()->route('colors.index')->with('custom_success', 'New Color has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Color', 'View color details id: ' . $id);
        $color = Color::find($id);
        return view('colors.show', ['color' => $color]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Color', 'Open Edit form of color id: ' . $id);
        $color = Color::find($id);
        return view('colors.edit', ['color' => $color]);
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
        $color = Color::findOrFail($id);

        // Update the item
        $color->name = $request->name;
        $color->code = $request->code;
        $color->save();

        Helper::logSystemActivity('Color', 'Edit color successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Color has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the color by $id
        $color = Color::findOrFail($id);

        // delete color
        $status = $color->delete();

        if ($status) {
            Helper::logSystemActivity('Color', 'Delete color successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Color has been deleted');
        } else {
            Helper::logSystemActivity('Color', 'Delete color failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Color was not deleted. Something went wrong.');
        }
    }
}
