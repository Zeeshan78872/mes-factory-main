<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemLog;
use App\Models\User;
use Helper;

class SystemLogController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check User Permissions
        $this->middleware('RolePermissionCheck:logs.view')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::all();
        $actionsArr = [
            'Auth',
            'BOM List',
            'Customers',
            'Department',
            'Supplier',
            'Inventory',
            'Job Orders',
            'Machine',
            'Multi-site',
            'Notifications',
            'Product Categories',
            'Product Units',
            'Production',
            'Products',
            'Purchase Order List',
            'Receiving Order List',
            'Site Location',
            'Stock Cards',
            'System Logs',
            'User Roles',
            'Users'
        ];

        Helper::logSystemActivity('System Logs', 'View system logs list');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];
        $filters['user'] = $request->user ?? 0;
        $filters['action_type'] = $request->action_type ?? 0;

        // Prepare the Logs Data
        $logs = SystemLog::with('user')
        ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
        ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
        ->when(!empty($filters['user']), function ($q) use ($filters) {
            return $q->where('action_by', $filters['user']);
        })
        ->when(!empty($filters['action_type']), function ($q) use ($filters) {
            return $q->where('action_on', $filters['action_type']);
        })
        ->orderBy('id', 'DESC')
        ->get();

        return view('system-logs.index', ['logs' => $logs,
            'filters' => $filters,
            'users' => $users,
            'actionsArr' => $actionsArr
        ]);
    }
}
