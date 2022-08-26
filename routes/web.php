<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\MultiSiteController;
use App\Http\Controllers\SiteLocationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStockCardController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\JobOrderBomController;
use App\Http\Controllers\JobOrderPurchaseController;
use App\Http\Controllers\JobOrderReceivingController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductUnitController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\QualityAssuranceController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\CostingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Default Landing Route
Route::get('/', function () {
    return redirect('/login');
});

// Auth Login
Auth::routes([
    // Disable Following Routes
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::middleware('auth')->group(function () {
    // User/Admin/Loggedin Routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/no-access', [HomeController::class, 'noAccess'])->name('no-access');

    // Manage Users
    Route::resource('users', UserController::class);
    Route::resource('user/roles', UserRoleController::class);

    // Manage Customers
    Route::get('/customers/ajax-delete-email/{id}', [CustomerController::class, 'deleteCustomerEmail'])->name('customers.delete.email');
    Route::get('/customers/ajax-delete-phone/{id}', [CustomerController::class, 'deleteCustomerPhone'])->name('customers.delete.phone');
    Route::get('/customers/ajax-search', [CustomerController::class, 'ajaxSearch'])->name('customers.search');
    Route::resource('customers', CustomerController::class);

    // Manage Products
    Route::get('/products/show1/{id}/{Order_id}', [ProductController::class, 'show1'])->name('products.show1');
    Route::get('/products/bom-mapping/{id}', [ProductController::class, 'bomMapping'])->name('products.bom.mapping');
    Route::post('/products/bom-mapping/{id}', [ProductController::class, 'storeBomMapping'])->name('products.bom.mapping.update');
    Route::post('/products/bom-mapping/upload/{id}', [ProductController::class, 'storeBomMappingUploadFile'])->name('products.bom.mapping.upload.file.update');
    Route::get('/products/ajax-delete-picture/{id}', [ProductController::class, 'deletePicture'])->name('products.delete.picture');
    Route::get('/products/ajax-search', [ProductController::class, 'ajaxSearch'])->name('products.search');
    Route::resource('products', ProductController::class);

    // Manage Products Categories & units
    Route::resource('product/categories', ProductCategoryController::class, ['as' => 'product']);
    Route::resource('product/units', ProductUnitController::class, ['as' => 'product']);

    // Product Ajax Stock Cards
    Route::get('/product/ajax-stock/{productId}', [ProductStockCardController::class, 'ajaxStockCards'])->name('product.stock.ajax');
    Route::resource('product/stock', ProductStockCardController::class, ['as' => 'product']);

    // Job Orders
    Route::get('/job-orders/get-packing-by-product', [JobOrderController::class, 'productPackingByOrder'])->name('job.products.packing');
    Route::get('/job-orders/duplicate/{id}', [JobOrderController::class, 'duplicate'])->name('job-orders.duplicate');
    Route::get('/job-orders/shipping/report', [JobOrderController::class, 'ordersShippingReport'])->name('job-orders.shipping.report');
    Route::get('/job-orders/ajax-search', [JobOrderController::class, 'ajaxSearch'])->name('job-orders.search');
    Route::get('/job-orders/add-products', [JobOrderController::class, 'addProducts'])->name('job-orders.addproducts');
    Route::get('/job-orders/report', [JobOrderController::class, 'ordersListReport'])->name('job-orders.orders.list.report');
    Route::delete('/job-orders/product/{jobOrderProductId}', [JobOrderController::class, 'destroyJobOrderProduct'])->name('job-orders.products.delete');
    Route::get('/job-orders/product/ajax-delete-picture/{id}', [JobOrderController::class, 'deleteProductPicture'])->name('job-orders.product.picture.delete');
    Route::resource('/job-orders', JobOrderController::class);
    
    Route::get('/job-orders/print/{id}', [JobOrderController::class, 'print'])->name('job-orders.print');

    // Job Orders BOM List
    Route::get('/job-order/bom/list/{order_id}/{product_id}', [JobOrderBomController::class, 'getBomList'])->name('job-order.bom.list');
    Route::resource('/job-order/bom', JobOrderBomController::class, ['as' => 'job-order'])->except(['update', 'edit', 'show']);

    // Job Orders Purchase List
    Route::get('/job-order/purchase/list/{order_id}/{product_id}', [JobOrderPurchaseController::class, 'getPurchaseList'])->name('job-order.purchase.list');
    Route::resource('/job-order/purchase', JobOrderPurchaseController::class, ['as' => 'job-order'])->except(['update', 'edit', 'show']);

    // Job Orders Receiving
    Route::get('/job-order/receiving/stock-card-print/{order_id}/{product_id}', [JobOrderReceivingController::class, 'stockCardPrint'])->name('job-order.receiving.stock.card.print');
    Route::get('/job-order/receiving/list/{order_id}/{product_id}', [JobOrderReceivingController::class, 'getReceivingList'])->name('job-order.receiving.list');
    Route::get('/job-order/receiving/table-data', [JobOrderReceivingController::class, 'getProductTable']);
    Route::get('/job-order/get-product-details', [JobOrderReceivingController::class, 'getProductDetails'])->name('job-order.get-product-details');
    Route::resource('/job-order/receiving', JobOrderReceivingController::class, ['as' => 'job-order']);
    

    // Inventory Routes
    Route::match(['get', 'post'], '/inventory/reports', [InventoryController::class, 'reports'])->name('inventory.reports');
    Route::get('/stock-cards', [ProductStockCardController::class, 'index'])->name('stock-cards.index');
    Route::get('/stock-card/ajax-search', [ProductStockCardController::class, 'ajaxSearch'])->name('stock-cards.ajax.search');
    Route::get('/stock-card/get-by-id', [ProductStockCardController::class, 'getStockCardDetails'])->name('stock-cards.get.id');
    Route::get('/stock-cards/create', [ProductStockCardController::class, 'create'])->name('stock-cards.create');
    Route::post('/stock-cards/store', [ProductStockCardController::class, 'store'])->name('stock-cards.store');
    Route::get('/inventory/delete-item-for-production/{id}', [InventoryController::class, 'destroyItemForProduction'])->name('inventory.delete.issued.item.for.production');
    Route::get('/inventory/issue-for-production', [InventoryController::class, 'issueForProduction'])->name('inventory.issue.for.production');
    Route::post('/inventory/issue-for-production', [InventoryController::class, 'storeIssueForProduction'])->name('inventory.store.issue.for.production');
    Route::get('/inventory/for-production', [InventoryController::class, 'getListForProduction'])->name('inventory.list.for.production');
    Route::get('/inventory/audit-items', [InventoryController::class, 'auditItems'])->name('inventory.audit-items');
    Route::get('/inventory/getProductsInventory', [InventoryController::class, 'getProductsInventory'])->name('inventory.getProductsInventory');
    Route::post('/inventory/updateAuditItems', [InventoryController::class, 'updateAuditItems'])->name('inventory.updateAuditItems');
    Route::resource('/inventory', InventoryController::class);
    Route::get('/inventory/return-from-production/{id}', [InventoryController::class, 'returnFromProduction'])->name('inventory.return.from.production');
    Route::post('/inventory/return-from-production/{id}', [InventoryController::class, 'storeReturnFromProduction'])->name('inventory.store.return.from.production');

    // Daily Production Routes
    Route::get('/productions/list', [ProductionController::class, 'index'])->name('productions.list');
    Route::get('/productions/stock-card', [ProductionController::class, 'getProductStockCard'])->name('productions.stock-card');
    Route::get('/productions/list/{id}', [ProductionController::class, 'edit'])->name('productions.edit');
    Route::get('/productions/delete-progress-chemical/{id}', [ProductionController::class, 'deleteProgressChemical'])->name('productions.delete.progress.chemical');
    Route::get('/productions/delete-progress/{id}', [ProductionController::class, 'deleteProgress'])->name('productions.delete.progress');
    Route::get('/production/daily/search', [ProductionController::class, 'ajaxDailySearch'])->name('production.daily.process.search.inprogress');
    Route::get('/production/daily', [ProductionController::class, 'daily'])->name('production.daily');
    Route::post('/production/daily', [ProductionController::class, 'dailyStore'])->name('production.daily.store');
    Route::get('/production/daily/{production_id}', [ProductionController::class, 'prodctionProcess'])->name('production.daily.process');
    Route::get('/production/daily/progresses/{production_id}', [ProductionController::class, 'prodctionProcessProgresses'])->name('production.daily.process.progresses');
    Route::post('/production/daily/progress/start', [ProductionController::class, 'prodctionProcessProgressStart'])->name('production.daily.progress.start');
    Route::post('/production/daily/progress/stop/{id}', [ProductionController::class, 'prodctionProcessProgressStop'])->name('production.daily.progress.stop');
    Route::post('/production/daily/progress/end/{production_id}', [ProductionController::class, 'prodctionProcessUpdate'])->name('production.daily.update');

    // Quality Assurance
    Route::post('/quality-assurance/report', [QualityAssuranceController::class, 'report'])->name('quality-assurance.report');
    Route::get('/quality-assurance/report', [QualityAssuranceController::class, 'report'])->name('quality-assurance.report');
    Route::get('/quality-assurance/start/{id}', [QualityAssuranceController::class, 'start'])->name('quality-assurance.start');
    Route::post('/quality-assurance/end/{id}', [QualityAssuranceController::class, 'end'])->name('quality-assurance.end');
    Route::resource('quality-assurance', QualityAssuranceController::class);

    // Shipping
    Route::get('/shipping/item/{QRcode}', [ShippingController::class, 'ajaxShippingItemShow'])->name('shippings.qr.show');
    Route::get('/shipping/progress/search', [ShippingController::class, 'ajaxShippingItemSearch'])->name('shippings.process.search');
    Route::get('/shipping/ajax-search/qr-code', [ShippingController::class, 'ajaxSearchQRCode'])->name('shippings.qr.search');
    Route::get('/shippings/scan-qr', [ShippingController::class, 'scanQR'])->name('shippings.scan-qr');
    Route::post('/shippings/process', [ShippingController::class, 'progressStore'])->name('shippings.process.store');
    Route::get('/shippings/process/{production_id}', [ShippingController::class, 'process'])->name('shippings.process');
    Route::get('/shippings/progresses/{shipping_id}', [ShippingController::class, 'processProgresses'])->name('shippings.process.progresses');
    Route::post('/shippings/progress/start', [ShippingController::class, 'processProgressStart'])->name('shippings.progress.start');
    Route::post('/shippings/progress/stop/{id}', [ShippingController::class, 'processProgressStop'])->name('shippings.progress.stop');
    Route::post('/shippings/progress/end/{shipping_id}', [ShippingController::class, 'processEnd'])->name('shippings.progress.end');
    Route::resource('shippings', ShippingController::class);

    // Costing Reports
    Route::match(['get', 'post'], '/costings/report/dashboard', [CostingController::class, 'dashboardReport'])->name('costing.reports.dashboard');
    Route::match(['get', 'post'], '/costings/report/daily-production', [CostingController::class, 'dailyProductionReport'])->name('costing.reports.daily.production');
    Route::match(['get', 'post'], '/costings/report/chemical-usage', [CostingController::class, 'chemicalUsageReport'])->name('costing.reports.chemical.usage');
    Route::match(['get', 'post'], '/costings/report/time-summary', [CostingController::class, 'timeSummaryReport'])->name('costing.reports.time.summary');
    Route::match(['get', 'post'], '/costings/report/purchase-cost', [CostingController::class, 'purchaseCostReport'])->name('costing.reports.purchase.cost');
    Route::match(['get', 'post'], '/costings/report/loading-cost', [CostingController::class, 'loadingCostReport'])->name('costing.reports.loading.cost');
    Route::match(['get', 'post'], '/costings/report/job-order-summary', [CostingController::class, 'jobOrderSummaryReport'])->name('costing.reports.job.order.summary.cost');

    // Get Server Date Time
    Route::get('/datetime', function () {
        return ['date_time'=> \Carbon\Carbon::Now()->format('Y-m-d H:i:s')];
    });

    // Notifications
    Route::get('/notifications/mark-seen', [NotificationController::class, 'markLastSeen'])->name('notifications.mark_seen');
    Route::resource('notifications', NotificationController::class)->only([
        'index', 'show'
    ]);

    // Multi Site Manage
    Route::resource('multi-sites', MultiSiteController::class);
    Route::resource('site-locations', SiteLocationController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('machines', MachineController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('materials', MaterialController::class);

    // System Settings
    Route::get('/system-settings/generate-qr/{code}', [SystemSettingController::class, 'generateQRCode'])->name('system.settings.qr.generator');
    Route::get('/system-settings', [SystemSettingController::class, 'edit'])->name('system.settings.edit');
    Route::put('/system-settings', [SystemSettingController::class, 'update'])->name('system.settings.update');

    // System Logs
    Route::get('/system-logs', [SystemLogController::class, 'index'])->name('system-logs.index');
});
