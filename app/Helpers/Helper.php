<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use App\Models\User;

class Helper
{
    public static function logSystemActivity($action_on, $action_type)
    {
        DB::table('system_logs')->insert([
            'action_on' => $action_on,
            'action_type' => $action_type,
            'action_from' => url()->full(),
            'action_by' => auth()->user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now() 
        ]);
    }

    public static function getPermissionsList()
    {
        return [
            'users' => ['add','edit','delete','view'],
            'roles' => ['add', 'edit', 'delete', 'view'],
            'multi-site' => ['add', 'edit', 'delete', 'view'],
            'site-locations' => ['add', 'edit', 'delete', 'view'],
            'departments' => ['add', 'edit', 'delete', 'view'],
            'suppliers' => ['add', 'edit', 'delete', 'view'],
            'colors' => ['add', 'edit', 'delete', 'view'],
            'materials' => ['add', 'edit', 'delete', 'view'],
            'machines' => ['add', 'edit', 'delete', 'view'],
            'job-orders' => ['add', 'edit', 'delete', 'view', 'manage-bom-list', 'manage-purchase-order', 'manage-receiving-order'],
            'bom-list' => ['add', 'edit', 'delete', 'view'],
            'inventory' => ['issue-for-production', 'audit'],
            'stock-cards' => ['view'],
            'customers' => ['add', 'edit', 'delete', 'view'],
            'products' => ['add', 'edit', 'delete', 'view', 'map-bom-list'],
            'product-categories' => ['add', 'edit', 'delete', 'view'],
            'product-units' => ['add', 'edit', 'delete', 'view'],
            'quality-assurance' => ['perform-QA', 'view', 'reports'],
            'daily-production' => ['edit', 'manage', 'view'],
            'shipping' => ['create-shipment', 'progress-tracking'],
            'costing' => ['daily-production-report'],
            'notifications' => ['view'],
            'logs' => ['view'],
            'system-settings' => ['edit']
        ];
    }

    public static function checkIfUserHasPermissionOf($checkPermission)
    {
        $roleAndPermission = explode('.', $checkPermission);
        $userPermissions = (json_decode(auth()->user()->role->permissions, TRUE))['permissions'];

        if(
            isset( $userPermissions[ $roleAndPermission[0] ][ $roleAndPermission[1] ] )
            && $userPermissions[ $roleAndPermission[0] ][ $roleAndPermission[1]] == 'on'
        ) {
            return true;
        }
        return false;
    }

    public static function getQATypes()
    {
        return [
            'N/A',
            'IQC',
            'IPQC',
            'FQC',
            'FINISHED GOODS INSPECTION'
        ];
    }

    public static function getQACategories()
    {
        return [
            'N/A',
            'RAW MATERIAL',
            'HARDWARE',
            'POLYFORM',
            'CARTON'
        ];
    }

    public static function getQAOptions()
    {
        return [
            'N/A',
            'Accepted',
            'Rejected',
            'Reworks',
            'Scrap',
            'Return to Supplier/Subcon',
        ];
    }

    public static function getQAGeneralSTDSample($lotSize)
    {
        $sampleSizes = [
            [
                'from' => 2,
                'to' => 8,
                'sample' => 2
            ],
            [
                'from' => 9,
                'to' => 15,
                'sample' => 3
            ],
            [
                'from' => 16,
                'to' => 25,
                'sample' => 5
            ],
            [
                'from' => 26,
                'to' => 50,
                'sample' => 8
            ],
            [
                'from' => 51,
                'to' => 90,
                'sample' => 13
            ],
            [
                'from' => 91,
                'to' => 150,
                'sample' => 20
            ],
            [
                'from' => 151,
                'to' => 280,
                'sample' => 32
            ],
            [
                'from' => 281,
                'to' => 500,
                'sample' => 50
            ],
            [
                'from' => 501,
                'to' => 1200,
                'sample' => 80
            ],
            [
                'from' => 1201,
                'to' => 3200,
                'sample' => 125
            ],
            [
                'from' => 3201,
                'to' => 10000,
                'sample' => 200
            ],
            [
                'from' => 10001,
                'to' => 35000,
                'sample' => 315
            ],
            [
                'from' => 35001,
                'to' => 150000,
                'sample' => 500
            ],
            [
                'from' => 150001,
                'to' => 5000000,
                'sample' => 800
            ],
            [
                'from' => 5000001,
                'to' => 999999999999,
                'sample' => 1250
            ]
        ];

        $sampleSizeResult = 0;
        foreach($sampleSizes as $size) {
            if($lotSize >= $size['from'] && $lotSize <= $size['to']) {
                $sampleSizeResult = $size['sample'];
                break;
            }
        }
        return $sampleSizeResult;
    }

    public static function getQASpecialSTDSample($lotSize)
    {
        $sampleSizes = [
            [
                'from' => 2,
                'to' => 8,
                'sample' => 2
            ],
            [
                'from' => 9,
                'to' => 15,
                'sample' => 2
            ],
            [
                'from' => 16,
                'to' => 25,
                'sample' => 3
            ],
            [
                'from' => 26,
                'to' => 50,
                'sample' => 3
            ],
            [
                'from' => 51,
                'to' => 90,
                'sample' => 5
            ],
            [
                'from' => 91,
                'to' => 150,
                'sample' => 5
            ],
            [
                'from' => 151,
                'to' => 280,
                'sample' => 8
            ],
            [
                'from' => 281,
                'to' => 500,
                'sample' => 8
            ],
            [
                'from' => 501,
                'to' => 1200,
                'sample' => 13
            ],
            [
                'from' => 1201,
                'to' => 3200,
                'sample' => 13
            ],
            [
                'from' => 3201,
                'to' => 10000,
                'sample' => 20
            ],
            [
                'from' => 10001,
                'to' => 35000,
                'sample' => 20
            ],
            [
                'from' => 35001,
                'to' => 150000,
                'sample' => 32
            ],
            [
                'from' => 150001,
                'to' => 5000000,
                'sample' => 32
            ],
            [
                'from' => 5000001,
                'to' => 999999999999,
                'sample' => 50
            ]
        ];

        $sampleSizeResult = 0;
        foreach ($sampleSizes as $size) {
            if ($lotSize >= $size['from'] && $lotSize <= $size['to']) {
                $sampleSizeResult = $size['sample'];
                break;
            }
        }
        return $sampleSizeResult;
    }
}
