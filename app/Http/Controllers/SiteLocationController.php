<?php

namespace App\Http\Controllers;

use App\Models\SiteLocation;
use Illuminate\Http\Request;
use Helper;

class SiteLocationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check SiteLocation Permissions
        $this->middleware('RolePermissionCheck:site-locations.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:site-locations.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:site-locations.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:site-locations.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siteLocations = SiteLocation::get();
        Helper::logSystemActivity('Site Location', 'Site Locations list view');
        return view('site-locations.index', ['siteLocations' => $siteLocations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Site Location', 'Add new form open');
        return view('site-locations.create');
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
        $siteLocation = new SiteLocation;
        $siteLocation->name = $request->name;
        $siteLocation->code = $request->code;
        $siteLocation->save();

        Helper::logSystemActivity('Site Location', 'Add new site successfully');

        // Back to index with success
        return redirect()->route('site-locations.index')->with('custom_success', 'New Site has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Site Location', 'View site location details id: ' . $id);
        $siteLocation = SiteLocation::find($id);
        return view('site-locations.show', ['siteLocation' => $siteLocation]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Site Location', 'Open Edit form of site location id: ' . $id);
        $siteLocation = SiteLocation::find($id);
        return view('site-locations.edit', ['siteLocation' => $siteLocation]);
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
        $siteLocation = SiteLocation::findOrFail($id);

        // Update the item
        $siteLocation->name = $request->name;
        $siteLocation->code = $request->code;
        $siteLocation->save();

        Helper::logSystemActivity('Site Location', 'Edit site location successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Site Location has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the siteLocation by $id
        $siteLocation = SiteLocation::findOrFail($id);

        // delete siteLocation
        $status = $siteLocation->delete();

        if ($status) {
            Helper::logSystemActivity('Site Location', 'Delete site location successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Site Location has been deleted');
        } else {
            Helper::logSystemActivity('Site Location', 'Delete site location failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Site Location was not deleted. Something went wrong.');
        }
    }
}
