<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultiSite;
use Helper;

class MultiSiteController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check MultiSite Permissions
        $this->middleware('RolePermissionCheck:multi-site.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:multi-site.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:multi-site.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:multi-site.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $multiSites = MultiSite::get();
        Helper::logSystemActivity('Multi-site', 'Multi Sites list view');
        return view('multi-sites.index', ['multiSites' => $multiSites]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Multi-site', 'Add new form open');
        return view('multi-sites.create');
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
        $multiSite = new MultiSite;
        $multiSite->name = $request->name;
        $multiSite->code = $request->code;
        $multiSite->save();

        Helper::logSystemActivity('Multi-site', 'Add new site successfully');

        // Back to index with success
        return redirect()->route('multi-sites.index')->with('custom_success', 'New Site has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Multi-site', 'View multi site details id: ' . $id);
        $multiSite = MultiSite::find($id);
        return view('multi-sites.show', ['multiSite' => $multiSite]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Multi-site', 'Open Edit form of multi site id: ' . $id);
        $multiSite = MultiSite::find($id);
        return view('multi-sites.edit', ['multiSite' => $multiSite]);
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
        $multiSite = MultiSite::findOrFail($id);

        // Update the item
        $multiSite->name = $request->name;
        $multiSite->code = $request->code;
        $multiSite->save();

        Helper::logSystemActivity('Multi-site', 'Edit multi site successfully id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Site has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the multiSite by $id
        $multiSite = MultiSite::findOrFail($id);

        // delete multiSite
        $status = $multiSite->delete();

        if ($status) {
            Helper::logSystemActivity('Multi-site', 'Delete multi site successfully id: ' . $id);

            // If success
            return back()->with('custom_success', 'Site has been deleted');
        } else {
            Helper::logSystemActivity('Multi-site', 'Delete multi site failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Site was not deleted. Something went wrong.');
        }
    }
}
