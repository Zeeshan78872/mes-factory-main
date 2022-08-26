<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationViewedBy;
use Helper;
use Auth;

class NotificationController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check MultiSite Permissions
        $this->middleware('RolePermissionCheck:notifications.view')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Notifications', 'View all notifications list');

        $notifications = Notification::with('viewers')->orderBy('id', 'desc')->get();
        return view('notifications.index', ['notifications' => $notifications]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        Helper::logSystemActivity('Notifications', 'View Notification id: ' . $id);

        $notification = Notification::find($id);

        // Record Viewer
        NotificationViewedBy::updateOrCreate(
            ['user_id' => $request->user()->id, 'notification_id' => $id],
            ['user_id' => $request->user()->id, 'notification_id' => $id]
        );

        return view('notifications.show', ['notification' => $notification]);
    }

    /**
     * Mark Last seen notification of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function markLastSeen()
    {
        $user = Auth::user();
        $user->last_seen_at = \Carbon\Carbon::now()->toDateTimeString();
        $user->save();
    }
}
