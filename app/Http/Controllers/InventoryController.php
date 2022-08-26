<?php

namespace App\Http\Controllers;

use App\Models\ProductionIssuedItem;
use App\Models\MultiSite;
use App\Models\SiteLocation;
use App\Models\Machine;
use App\Models\Department;
use App\Models\ProductStockCard;
use App\Models\ProductStock;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\InventoryAudit;
use Illuminate\Http\Request;
use DB;
use Helper;

class InventoryController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Department Permissions
        $this->middleware('RolePermissionCheck:inventory.issue-for-production')->only(['getListForProduction', 'issueForProduction', 'storeIssueForProduction']);
        $this->middleware('RolePermissionCheck:inventory.audit')->only(['auditItems', 'updateAuditItems', 'getProductsInventory']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListForProduction()
    {
        $inventoryIssuedForProductions = ProductionIssuedItem::with([
            'stockCard',
            'site',
            'location',
            'department',
            'machine'
            ])->orderBy('id', 'desc')->get();
        Helper::logSystemActivity('Inventory', 'Inventory Issued for Production list view');

        return view('inventory.issued-for-production-list', [
            'inventoryIssuedForProductions' => $inventoryIssuedForProductions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function issueForProduction()
    {
        $stockCards = ProductStockCard::get();
        $multiSites = MultiSite::get();
        $siteLocations = SiteLocation::get();
        $machines = Machine::get();
        $departments = Department::get();

        Helper::logSystemActivity('Inventory', 'Issue item for production form open');
        return view('inventory.issue-item-for-production', [
            'stockCards' => $stockCards,
            'multiSites' => $multiSites,
            'siteLocations' => $siteLocations,
            'machines' => $machines,
            'departments' => $departments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeIssueForProduction(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'stock_card_id' => 'required|exists:product_stock_cards,id',
            'quantity' => 'required|numeric',
            'site_id' => 'required|exists:sites,id',
            'site_location_id' => 'required|exists:site_locations,id',
            'department_id' => 'required|exists:departments,id',
            'machine_id' => 'required|exists:machines,id'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $itemIssued = new ProductionIssuedItem;
        $itemIssued->stock_card_id = $request->stock_card_id;
        $itemIssued->quantity = $request->quantity;
        $itemIssued->site_id = $request->site_id;
        $itemIssued->site_location_id = $request->site_location_id;
        $itemIssued->department_id = $request->department_id;
        $itemIssued->machine_id = $request->machine_id;
        $itemIssued->save();

        // Update Stock Card
        $stockCard = ProductStockCard::where('id', $request->stock_card_id)->first();
        $stockCard->available_quantity = $stockCard->available_quantity - $request->quantity;
        $stockCard->save();

        // Update Product Stock
        $productStock = ProductStock::where('product_id', '=', $stockCard->product_id)->first();
        $productStock->quantity = $productStock->quantity - $request->quantity;
        $productStock->save();

        // Inventory Audit Logs
        $inventoryAudit = new InventoryAudit;
        $inventoryAudit->stock_card_id = $request->stock_card_id;
        $inventoryAudit->site_id = $request->site_id;
        $inventoryAudit->site_location_id = $request->site_location_id;
        $inventoryAudit->department_id = $request->department_id;
        $inventoryAudit->quantity = $request->quantity;
        $inventoryAudit->movement_type = 2;
        $inventoryAudit->save();

        Helper::logSystemActivity('Inventory', 'Item Issued for Production successfully');

        // Back to index with success
        return redirect()->route('inventory.list.for.production')->with('custom_success', 'Item Issued for Production successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function auditItems()
    {
        Helper::logSystemActivity('Inventory', 'Audit Form view');

        $products = Product::get();
        $categories = ProductCategory::get();
        $stockCards = ProductStockCard::get();

        return view('inventory.audit-items', [
            'categories' => $categories,
            'stockCards' => $stockCards,
            'products' => $products
            ]);
    }
    

    /**
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAuditItems(Request $request)
    {
        Helper::logSystemActivity('Inventory', 'Update Audit Products');

        // Validations
        $validatedData = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'tally_in.*' => 'required|numeric',
            'tally_out.*' => 'required|numeric'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        $totalItems = count($request->product_id);
        for($i=0; $i < $totalItems; $i++) {
            $productId = $request->product_id[$i];
            $sc_id = $request->sc_id[$i];
            $tallyIn = $request->tally_in[$i];
            $tallyOut = $request->tally_out[$i];


            $productStock = ProductStock::where('product_id', $productId)->first();
            // Update the item
            $productStock->quantity = (($productStock->quantity + $tallyIn) - $tallyOut);
            $productStock->save();

            $productStockCard = ProductStockCard::where('id',$sc_id)->first();
            $productStockCard->available_quantity = (($productStockCard->available_quantity + $tallyIn) - $tallyOut);
            $productStockCard->save();


            Helper::logSystemActivity('Inventory', 'Updated Audit Product ID: ' . $productId. ' Tally In:' . $tallyIn . ' Tally Out:'. $tallyOut);
        }

        Helper::logSystemActivity('Inventory', 'Update Audit Products successfull');

        // Back to index with success
        return back()->with('custom_success', 'Update Audit Products successfully!');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProductsInventory(Request $request)
    {
        Helper::logSystemActivity('Inventory', 'Get Audit List of Stock');

        $productId = $request->productId;
        $stockCardId = $request->stockCardId;
        $categoryId = $request->categoryId;
        $catType = $request->catType;
        $productIds = null;

            // $productIds = ProductStockCard::whereIn('id', explode(',',$stockCardId))->pluck('product_id')->toArray();
        
        $products = DB::select('
                        select products.id as pid,products.model_name,products.color_name,concat(products.length," ",products.length_unit) as length_unit,
                        concat(products.width," ",products.width_unit) as width_unit,concat(products.thick," ",products.thick_unit) as thick_unit,
                        product_stock_cards.id as sc_id,product_stock_cards.stock_card_number,product_stocks.quantity,product_stocks.id as ps_id from products inner join 
                            product_stock_cards on products.id = product_stock_cards.product_id
                            inner join product_stocks on products.id = product_stocks.product_id
                        where product_stock_cards.id in ('.$stockCardId.')
                    ');

        return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnFromProduction($id)
    {
        $item = ProductionIssuedItem::findOrFail($id);
        Helper::logSystemActivity('Inventory', 'Return item from production form');
        return view('inventory.return-item-from-production', ['item' => $item]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeReturnFromProduction(Request $request, $id)
    {
        // Validations
        $validatedData = $request->validate([
            'quantity' => 'required|numeric'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $itemIssued = ProductionIssuedItem::findOrFail($id);
        $itemIssued->quantity = $itemIssued->quantity - $request->quantity;
        $itemIssued->save();

        // Update Stock
        $stockCard = ProductStockCard::where('id', $itemIssued->stock_card_id)->first();
        $stockCard->available_quantity = $stockCard->available_quantity + $request->quantity;
        $stockCard->save();

        // Update Product Stock
        $productStock = ProductStock::where('product_id', '=', $stockCard->product_id)->first();
        $productStock->quantity = $productStock->quantity + $request->quantity;
        $productStock->save();

        // Inventory Audit Logs
        $inventoryAudit = new InventoryAudit;
        $inventoryAudit->stock_card_id = $itemIssued->stock_card_id;
        $inventoryAudit->quantity = $request->quantity;
        $inventoryAudit->movement_type = 1;
        $inventoryAudit->remarks = $request->remarks;
        $inventoryAudit->save();

        Helper::logSystemActivity('Inventory', 'Item Return from Production successfully');

        // Back to index with success
        return redirect()->route('inventory.list.for.production')->with('custom_success', 'Item Return from Production successfully');
    }

    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function old_reports(Request $request)
    {
        Helper::logSystemActivity('Inventory', 'Open Inventory report generate page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];

        // Total Months
        $totalMonths = (\Carbon\Carbon::createFromDate($filters['dateRanges']['start'])
                        ->diff($filters['dateRanges']['end'])
                        ->format('%m')) + 1;

        // Dates Range
        $datesPeriod = \Carbon\CarbonPeriod::create($filters['dateRanges']['start'], $filters['dateRanges']['end']);
        $datesArr = [];
        // Iterate over the period
        foreach ($datesPeriod as $date) {
            array_push($datesArr, $date->format('Y-m-d'));
        }

        $totalInResults = InventoryAudit::with(['site', 'location','department', 'stockCard.product'])
                ->where('movement_type', 1)
                ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                ->groupBy('stock_card_id')
                ->get();
        $totalOutResults = InventoryAudit::with(['site', 'location','department', 'stockCard.product'])
                ->where('movement_type', 2)
                ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                ->groupBy('stock_card_id')
                ->get();

        $finalResults = [];
        $bringForwardResults = [];
        foreach($totalInResults as $totalInResult) {
            $date = $totalInResult->created_at->format('Y-m-d');
            $qty = $totalInResult->quantity;
            $stock_card_id = $totalInResult->stock_card_id;

            $bringForward = ProductStockCard::whereDate('created_at', '<=', $filters['dateRanges']['start'])->sum('available_quantity');
            $productDetails = ProductStockCard::with(['product'])->findOrFail($stock_card_id);

            $finalResults[$date]['in']['qty'] = $qty;
            $finalResults[$date]['in']['stock_card_id'] = $stock_card_id;
            $finalResults[$date]['in']['product'] = $productDetails;
            
            $bringForwardResultsArr = [
                'amount' => $bringForward
            ];
            array_push($bringForwardResults, $bringForwardResultsArr);
        }

        $lastOutDate = null;
        foreach ($totalOutResults as $totalOutResult) {
            $date = $totalOutResult->created_at->format('Y-m-d');
            $qty = $totalOutResult->quantity;
            $stock_card_id = $totalOutResult->stock_card_id;

            $finalResults[$date]['out']['qty'] = $qty;
            $finalResults[$date]['out']['stock_card_id'] = $stock_card_id;
            $finalResults[$date]['out']['site'] = $totalOutResult->site;
            $finalResults[$date]['out']['location'] = $totalOutResult->location;
            $finalResults[$date]['out']['department'] = $totalOutResult->department;
            
            $lastOutDate = $date;
        }

        return view('inventory.reports', [
            'filters' => $filters,
            'totalMonths' => $totalMonths,
            'datesRange' => $datesArr,
            'totalDates' => count($datesArr),
            'results' => $finalResults,
            'lastOutDate' => $lastOutDate,
            'bringForwardResults' => $bringForwardResults
        ]);
    }

    /**
     * Display a listing of the resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request)
    {
        Helper::logSystemActivity('Inventory', 'Open Inventory report generate page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];

        // Total Months
        $totalMonths = (\Carbon\Carbon::createFromDate($filters['dateRanges']['start'])
        ->diff($filters['dateRanges']['end'])
        ->format('%m')) + 1;

        // Dates Range
        $datesPeriod = \Carbon\CarbonPeriod::create($filters['dateRanges']['start'], $filters['dateRanges']['end']);
        $datesArr = [];
        // Iterate over the period
        foreach ($datesPeriod as $date) {
            array_push($datesArr, $date->format('Y-m-d'));
        }

        $totalInResults = InventoryAudit::with(['site', 'location', 'department', 'stockCard.product'])
        ->where('movement_type', 1)
        ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
        ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
        ->groupBy('stock_card_id')
        ->get();
        $totalOutResults = InventoryAudit::with(['site', 'location', 'department', 'stockCard.product'])
        ->where('movement_type', 2)
        ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
        ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
        ->groupBy('stock_card_id')
        ->get();

        return view('inventory.reports', [
            'filters' => $filters,
            'totalMonths' => $totalMonths,
            'datesRange' => $datesArr,
            'totalDates' => count($datesArr),
            'totalInResults' => $totalInResults,
            'totalOutResults' => $totalOutResults
        ]);
    }
}
