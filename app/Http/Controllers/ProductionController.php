<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Department;
use App\Models\ProductStock;
use App\Models\ProductStockCard;
use App\Models\DailyProduction;
use App\Models\DailyProductionChemical;
use App\Models\DailyProductionMachine;
use App\Models\DailyProductionProgress;
use App\Models\DailyProductionStockCard;
use App\Models\DailyProductionWorker;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InventoryAudit;
use DB;
use Helper;

class ProductionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:daily-production.edit')->only(['deleteProgress', 'deleteProgressChemical']);
        $this->middleware('RolePermissionCheck:daily-production.view')->only(['index', 'daily', 'ajaxDailySearch']);
        $this->middleware('RolePermissionCheck:daily-production.manage')->only(['dailyStore', 'prodctionProcess', 'prodctionProcessProgresses', 'prodctionProcessProgressStart', 'prodctionProcessProgressStop', 'prodctionProcessUpdate']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productions = DailyProduction::with(['stockCards', 'department', 'workers', 'machines'])->get();
        Helper::logSystemActivity('Production', 'All Productions list view');
        return view('production.index', ['productions' => $productions]);
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
        Helper::logSystemActivity('Production', 'Open Edit form of Production id: ' . $id);
        $production = DailyProduction::with(['chemicals.chemical.product','stockCards', 'department', 'workers', 'machines', 'progresses'])->find($id);
        return view('production.edit', ['productionItem' => $production]);
    }


    /**
     * Delete the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProgress($id)
    {
        Helper::logSystemActivity('Production', 'Delete Progress id: ' . $id);
        $progress = DailyProductionProgress::findOrFail($id);
        $status = $progress->delete();
        if ($status) {
            Helper::logSystemActivity('Production', 'Delete Production progress successfully id: ' . $id);
            // If success
            return back()->with('custom_success', 'Production progress has been deleted');
        } else {
            Helper::logSystemActivity('Production', 'Delete Production progress failed id: ' . $id);
            // If no success
            return back()->with('custom_errors', 'Production progress was not deleted. Something went wrong.');
        }
    }

    /**
     * Delete the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProgressChemical($id)
    {
        Helper::logSystemActivity('Production', 'Delete Progress Chemical id: ' . $id);
        $chemical = DailyProductionChemical::findOrFail($id);
        $status = $chemical->delete();
        if ($status) {
            Helper::logSystemActivity('Production', 'Delete Production progress Chemical successfully id: ' . $id);
            // If success
            return back()->with('custom_success', 'Production progress Chemical has been deleted');
        } else {
            Helper::logSystemActivity('Production', 'Delete Production progress Chemical failed id: ' . $id);
            // If no success
            return back()->with('custom_errors', 'Production progress Chemical was not deleted. Something went wrong.');
        }
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
    public function daily()
    {
        $machines = Machine::get();
        $departments = Department::get();
        $workers = User::get();
        $stockCards = ProductStockCard::with('product')->get();

        Helper::logSystemActivity('Production', 'Daily production page open');
        return view('production.daily', [
            'workers' => $workers,
            'machines' => $machines,
            'stockCards' => $stockCards,
            'departments' => $departments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dailyStore(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'department' => 'required|exists:departments,id',
            'workStatus' => 'required|numeric',
            'stock_card_ids.*' => 'required|exists:product_stock_cards,id',
            'machine_ids.*' => 'nullable|exists:machines,id',
            'worker_ids.*' => 'required|exists:users,id',
            'total_plan_qty' => 'required|numeric'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $dailyProduction = new DailyProduction;
        $dailyProduction->department_id = $request->department;
        $dailyProduction->total_quantity_plan = $request->total_plan_qty;
        $dailyProduction->testing_speed = $request->testing_speed;
        $dailyProduction->work_status = $request->workStatus;
        $dailyProduction->created_by = $request->user()->id;
        $dailyProduction->updated_by = $request->user()->id;
        $dailyProduction->save();
        $dailyProductionId = $dailyProduction->id;

        // Child Items
        $machineIds = $request->machine_ids;
        $workerIds = $request->worker_ids;
        $stockCardIds = $request->stock_card_ids;

        if (!empty($stockCardIds)) {
            $insertStockCardDataArr = [];
            foreach ($stockCardIds as $stockCardId) {
                array_push($insertStockCardDataArr, ['daily_production_id' => $dailyProductionId, 'stock_card_id' => $stockCardId]);
            }
            DailyProductionStockCard::insert($insertStockCardDataArr);
        }

        if(!empty($machineIds)) {
            $insertMachineDataArr = [];
            foreach($machineIds as $machineId) {
                array_push($insertMachineDataArr, ['daily_production_id' => $dailyProductionId, 'machine_id' => $machineId ]);
            }
            DailyProductionMachine::insert($insertMachineDataArr);
        }

        if (!empty($workerIds)) {
            $insertWorkerDataArr = [];
            foreach ($workerIds as $workerId) {
                array_push($insertWorkerDataArr, ['daily_production_id' => $dailyProductionId, 'worker_id' => $workerId]);
            }
            DailyProductionWorker::insert($insertWorkerDataArr);
        }

        Helper::logSystemActivity('Production', 'Daily production Item created successfully');

        // Back to index with success
        return redirect()->route('production.daily.process', $dailyProductionId)->with('custom_success', 'Daily Production Item Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function prodctionProcess($id)
    {
        Helper::logSystemActivity('Production', 'Working on item, Production ID: ' . $id);

        $productionItem = DailyProduction::with([
            'workers.worker',
            'machines.machine',
            'stockCards.stockCard',
            'department'
            ])
            ->where('is_ended',0)
            ->findOrFail($id);

        return view('production.process', [
            'productionItem' => $productionItem
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function prodctionProcessProgresses($id)
    {
        return DailyProduction::with(['progresses'])->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prodctionProcessProgressStart(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'timer_type' => 'required|integer',
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'production_id' => 'required|exists:daily_productions,id'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $startDate = $request->start_date;

        $dailyProductionProgress = new DailyProductionProgress;
        $dailyProductionProgress->daily_production_id = $request->production_id;
        $dailyProductionProgress->timer_type = $request->timer_type;
        $dailyProductionProgress->started_at = $startDate;
        $dailyProductionProgress->save();

        Helper::logSystemActivity('Production', 'Daily production Progress Started successfully');

        return ['status' => true, 'id'=> $dailyProductionProgress->id ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prodctionProcessProgressStop(Request $request, $id)
    {
        $dailyProductionProgress = DailyProductionProgress::where('id', $id)->first();

        // Store the item
        $startDate = $dailyProductionProgress->started_at;
        $endDate = \Carbon\Carbon::Now()->format('Y-m-d H:i:s');

        // Update
        $dailyProductionProgress->ended_at = $endDate;
        $dailyProductionProgress->difference_seconds = \Carbon\Carbon::parse($endDate)->diffInSeconds(\Carbon\Carbon::parse($startDate));
        $dailyProductionProgress->save();

        Helper::logSystemActivity('Production', 'Daily production Progress Ended successfully');

        return ['status'=>true];
    }

    /**
     * Update the specified resource in storage.
     *
     * END Production
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function prodctionProcessUpdate(Request $request, $id)
    {
        // Validations
        $validatedData = $request->validate([
            'total_qty_produce' => 'required|array',
            'total_qty_reject' => 'required|array',
            'testing_speed' => 'required',
            
            // Conditionally Required fields
            // If assembly chemicals 
            'chemical_stock_card_ids.*' => 'exclude_if:has_chemical,0|required|exists:product_stock_cards,id',
            'chemical_method.*' => 'exclude_if:has_chemical,0|required|string',
            'chemical_qty.*' => 'exclude_if:has_chemical,0|required|integer',
            // If assembly
            'assembled_product_id.*' => 'exclude_if:has_assembly,0|required|exists:products,id',
        ]);
        
        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $dailyProduction = DailyProduction::with('department', 'stockCards.stockCard')->findOrFail($id);
        $isChemical = $dailyProduction->department->is_chemical;
        $isAssembly = $dailyProduction->department->is_assembly;
        // Update the item
        $dailyProduction->testing_speed = $request->testing_speed;
        $dailyProduction->total_quantity_produced = $request->total_qty_produce[0];
        $dailyProduction->total_quantity_rejected = $request->total_qty_reject[0];
        $dailyProduction->is_ended = 1;
        $dailyProduction->save();

        // Daily Production ID
        $dailyProductionId = $dailyProduction->id;

        // Save Chemicals
        if($isChemical) {
            $totalChemicalStockCardIds = count($request->chemical_stock_card_ids);

            if ($totalChemicalStockCardIds > 0) {
                $insertChemicalStockCardIdsDataArr = [];
                for ($i = 0; $i < $totalChemicalStockCardIds; $i++) {
                    array_push($insertChemicalStockCardIdsDataArr, [
                        'daily_production_id' => $dailyProductionId,
                        'stock_card_id' => $request->chemical_stock_card_ids[$i],
                        'method' => $request->chemical_method[$i],
                        'quantity' => $request->chemical_qty[$i]
                    ]);
                }
                DailyProductionChemical::insert($insertChemicalStockCardIdsDataArr);
            }
        }

        $dailyProductionOrderId = null;

        foreach($dailyProduction->stockCards as $stockCard) {
            $stockCardDetails = ProductStockCard::where('id', $stockCard->stock_card_id)->first();
            $maxId = ProductStockCard::max('id');
            $newId = $maxId + 1;
            $dailyProductionOrderId = $stockCardDetails->order_id;
            // Create Stock Cards
            ProductStockCard::updateOrCreate(
                [
                    'product_id' => $stockCardDetails->product_id,
                    'order_id' => $stockCardDetails->order_id,
                    'is_rejected' => 0,
                    'is_balance' => 0,
                    'stock_card_number' => $stockCardDetails->stock_card_number . '-A'
                ],
                [
                    'stock_card_number' => $stockCardDetails->stock_card_number . '-A',
                    'ordered_quantity' => $dailyProduction->total_quantity_plan,
                    'available_quantity' => $request->total_qty_produce[0],
                    'order_id' => $stockCardDetails->order_id,
                    'product_id' => $stockCardDetails->product_id,
                    'date_in' => date('Y-m-d'),
                    'is_rejected' => 0,
                    'is_balance' => 0
                ]
            );

            // Update Product Stock
            $productStock = ProductStock::where('product_id', '=', $stockCardDetails->product_id)->first();
            if (empty($productStock)) {
                $productStock = new ProductStock;
                $productStock->product_id = $stockCardDetails->product_id;
                $productStock->quantity = $request->total_qty_produce[0];
            } else {
                $productStock->quantity = $productStock->quantity + $request->total_qty_produce[0];
            }
            $productStock->save();

            // Update Inventory Audit Report
            $stockCard = ProductStockCard::where('product_id', $stockCardDetails->product_id)
                ->where('order_id', $stockCardDetails->order_id)
                ->where('date_in', date('Y-m-d'))
                ->where('is_rejected', 0)
                ->where('is_balance', 0)
                ->first();
            // Inventory Audit Logs
            $inventoryAudit = new InventoryAudit;
            $inventoryAudit->stock_card_id = $stockCard->id;
            $inventoryAudit->quantity = $request->total_qty_produce[0];
            $inventoryAudit->movement_type = 1;
            $inventoryAudit->save();

            if(!empty($request->total_qty_reject)) {
                $maxId = ProductStockCard::max('id');
                $newId = $maxId + 1;
                // Create Stock Cards
                ProductStockCard::updateOrCreate(
                    [
                        'product_id' => $stockCardDetails->product_id,
                        'order_id' => $stockCardDetails->order_id,
                        'is_rejected' => 1,
                        'is_balance' => 0,
                        'stock_card_number' => $stockCardDetails->stock_card_number . '-R'
                    ],
                    [
                        'stock_card_number' => $stockCardDetails->stock_card_number . '-R',
                        'ordered_quantity' => $dailyProduction->total_quantity_plan,
                        'available_quantity' => $request->total_qty_reject[0],
                        'order_id' => $stockCardDetails->order_id,
                        'product_id' => $stockCardDetails->product_id,
                        'date_in' => date('Y-m-d'),
                        'is_rejected' => 1,
                        'is_balance' => 0
                    ]
                );
            }

            break;
        }

        // Perform Assembly Actions
        if($isAssembly) {
            foreach ($request->input('select_stock_card') as $iteration=>$stock_list){
                if($stock_list=="create_new"){
                    // Store the item
                    $maxId = ProductStockCard::max('id');
                    $newId = $maxId + 1;
                    $productStockCard = new ProductStockCard;
                    $productStockCard->stock_card_number = date("Y") . '/' . $newId;
                    $productStockCard->ordered_quantity = $request->total_qty_produce[0];
                    $productStockCard->available_quantity = $request->chemical_qty[$iteration];
                    $productStockCard->product_id = $request->assembled_product_id[$iteration];
//                    $productStockCard->order_id = $dailyProductionOrderId;
                    $productStockCard->date_in = date('Y-m-d H:i:s');
                    $productStockCard->save();

                    // Update Product Stock
                    $productStock = ProductStock::where('product_id', '=', $request->assembled_product_id[$iteration])->first();
                    if (empty($productStock)) {
                        $productStock = new ProductStock;
                        $productStock->product_id = $request->assembled_product_id[$iteration];
                        $productStock->quantity = $request->total_qty_produce[0];
                    } else {
                        $productStock->quantity = $productStock->quantity + $request->total_qty_produce[0];
                    }
                    $productStock->save();

                    // Update Inventory Audit Report
                    $stockCard = ProductStockCard::where('product_id', $request->assembled_product_id[$iteration])
                        ->where('order_id', $dailyProductionOrderId)
                        ->where('date_in', date('Y-m-d'))
                        ->where('is_rejected', 0)
                        ->where('is_balance', 0)
                        ->first();
                    // Inventory Audit Logs
                    $inventoryAudit = new InventoryAudit;
                    $inventoryAudit->stock_card_id = $stockCard->id;
                    $inventoryAudit->quantity = $request->total_qty_produce[0];
                    $inventoryAudit->movement_type = 1;
                    $inventoryAudit->save();
                }else{
                    //Update Data based on Selected Stock Card
                    $s = ProductStockCard::where(['id'=>$stock_list])->first();
                    $s['available_quantity'] = intval($s['available_quantity']) + $request->chemical_qty[$iteration];
                    $s->save();
                    // Inventory Audit Logs
                    $inventoryAudit = new InventoryAudit;
                    $inventoryAudit->stock_card_id = $stock_list;
                    $inventoryAudit->quantity = $request->total_qty_produce[0];
                    $inventoryAudit->movement_type = 1;
                    $inventoryAudit->save();
                }
            }



        }

        Helper::logSystemActivity('Production', 'Daily production ended successfully id: ' . $id);

        // Back to index with success
        return redirect()->route('production.daily')->with('custom_success', 'Daily production ended successfully');
    }

    /**
     * Ajax Search the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxDailySearch(Request $request) {
        $department_id = $request->department_id;
        $worker_id = $request->worker_id;
        $machine_id = $request->machine_id;
        $stock_card_id = $request->stock_card_id;
        $dailyProductionMachineIds = $dailyProductionWorkerIds = null;

        if (!empty($machine_id)) {
            $dailyProductionMachineIds = DailyProductionMachine::where('machine_id', $machine_id)->pluck('daily_production_id')->toArray();
        }
        if (!empty($worker_id)) {
            $dailyProductionWorkerIds = DailyProductionWorker::where('worker_id', $worker_id)->pluck('daily_production_id')->toArray();
        }
        if (!empty($stock_card_id)) {
            $dailyProductionStockCardIds = DailyProductionStockCard::where('stock_card_id', $stock_card_id)->pluck('daily_production_id')->toArray();
        }

        // Find the items
        $dailyProduction = DailyProduction::with([
            'workers.worker',
            'machines.machine',
            'stockCards.stockCard',
            'department'
        ])
        ->where('is_ended', '=', 0)
        ->whereHas('department', function ($query) use ($department_id) {
            return $query->where('id', '=', $department_id);
        })
        ->when($dailyProductionWorkerIds, function($query) use ($dailyProductionWorkerIds) {
            return $query->whereIn('id', $dailyProductionWorkerIds);
        })
        ->when($dailyProductionMachineIds, function ($query) use ($dailyProductionMachineIds) {
            return $query->whereIn('id', $dailyProductionMachineIds);
        })
        ->when($dailyProductionStockCardIds, function ($query) use ($dailyProductionStockCardIds) {
            return $query->whereIn('id', $dailyProductionStockCardIds);
        })
        ->limit(10)->orderBy('id','desc')->get();

        return $dailyProduction;
    }

    public function getProductStockCard(){
        $stockCards = DB::select('select product_stock_cards.*,products.product_name as product_name,products.model_name as model_name,products.color_name as color_name,products.color_code as color_code from product_stock_cards inner join products on product_stock_cards.product_id = products.id');
        echo json_encode($stockCards);
    }
}
