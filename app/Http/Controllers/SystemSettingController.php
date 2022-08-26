<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Helper;

class SystemSettingController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:system-settings.edit')->only(['edit', 'update']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemSetting  $systemSetting
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        Helper::logSystemActivity('System Settings', 'Open System Settings form');
        $systemSettings = SystemSetting::findOrFail(1);
        return view('system-settings.edit', ['systemSettings' => $systemSettings]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'basic_time_start' => 'nullable|date_format:H:i',
            'basic_time_end' => 'nullable|date_format:H:i',
            'over_time_start' => 'nullable|date_format:H:i',
            'over_time_end' => 'nullable|date_format:H:i'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $systemSettings = SystemSetting::findOrFail(1);

        // Update the item
        $systemSettings->basic_time_start = $request->basic_time_start;
        $systemSettings->basic_time_end = $request->basic_time_end;
        $systemSettings->over_time_start = $request->over_time_start;
        $systemSettings->over_time_end = $request->over_time_end;
        $systemSettings->save();

        Helper::logSystemActivity('System Settings', 'Edit System Settings successfully');

        // Back to index with success
        return back()->with('custom_success', 'System Settings has been updated successfully');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  INT $code 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateQRCode($code, Request $request)
    {
        return \QrCode::size(350)->generate($code);
    }
}
