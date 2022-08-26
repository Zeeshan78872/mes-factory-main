<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\JobOrderProduct;
use App\Models\Shipping;
use App\Models\ShippingItem;
use App\Models\ShippingLeftOver;
use App\Models\ShippingProgress;
use App\Models\ShippingReplacementPart;
use App\Models\ShippingStockCard;
use App\Models\User;
use Illuminate\Http\Request;
use Helper;


class ShippingController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:shipping.create-shipment')->only(['create', 'store', 'edit', 'update']);
        $this->middleware('RolePermissionCheck:shipping.progress-tracking')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Shipping', 'View all Shipping list');

        $shippings = Shipping::with(['jobOrder'])->orderBy('id', 'desc')->get();

        return view('shippings.index', [
            'shippings' => $shippings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Helper::logSystemActivity('Shipping', 'Started new Shipping Form');

        $loadToType = $request->load_to_type ?? 1;

        return view('shippings.create', [
            'loadToType' => $loadToType
        ]);
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
            'load_to' => 'required|integer',
            'order_id' => 'required|exists:job_orders,id',
            'truck_out_date' => 'nullable|date',
            'qc_date' => 'nullable|date',
            'booking_no' => 'nullable|string',
            'container_no' => 'nullable|string',
            'seal_no' => 'nullable|string',
            'vehicle_no' => 'nullable|string',
            'company' => 'nullable|string',
            'do_no' => 'nullable|string',
            'stock_cards.*' => 'required_if:load_to,2|exists:product_stock_cards,id',
            'description' => 'nullable|string|max:255',
            'leftover_po_no.*' => 'nullable|string',
            'leftover_model_product.*' => 'nullable|exists:products,id',
            'leftover_qty.*' => 'nullable|integer',
            'replace_model_product.*' => 'nullable|exists:products,id',
            'replace_qty.*' => 'nullable|integer'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $shipping = new Shipping;
        $shipping->load_to = $request->load_to;
        $shipping->order_id = $request->order_id;
        $shipping->truck_out_date = $request->truck_out_date;
        $shipping->qc_date = $request->qc_date;
        $shipping->booking_no = $request->booking_no;
        $shipping->container_no = $request->container_no;
        $shipping->seal_no = $request->seal_no;
        $shipping->vehicle_no = $request->vehicle_no;
        $shipping->company = $request->company;
        $shipping->do_no = $request->do_no;
        $shipping->description = $request->description;
        $shipping->save();
        $shippingId = $shipping->id;

        $nowTime = \Carbon\Carbon::now()->toDateTimeString();

        // If its Lorry then Required Stock Cards
        if($request->load_to == 2) {
            // Total Child Items
            $totalStockCards = count($request->stock_cards);

            $stockCardItems = [];
            for ($i = 0; $i < $totalStockCards; $i++) {
                $stockCardItem = [
                    'stock_card_id' => $request->stock_cards[$i],
                    'shipping_id' => $shippingId
                ];
                array_push($stockCardItems, $stockCardItem);
            }

            if (!empty($stockCardItems)) {
                ShippingStockCard::insert($stockCardItems);
            }
        }

        $shipmentItems = [];
        $jobProducts = JobOrderProduct::where('order_id', $request->order_id)->pluck('product_id')->toArray();
        $nowTime = \Carbon\Carbon::now()->toDateTimeString();
        foreach ($jobProducts as $productId) {
            $shipmentItem = [
                'product_id' => $productId,
                'shipping_id' => $shippingId,
                'qr_code' => abs(crc32(uniqid())),
                'created_at' => $nowTime
            ];
            array_push($shipmentItems, $shipmentItem);
        }

        if (!empty($shipmentItems)) {
            ShippingItem::insert($shipmentItems);
        }

        // Left Overs - Total Child Items
        $totalLeftOvers = count($request->leftover_po_no);
        
        $leftOverItems = [];
        if($totalLeftOvers > 0 && !empty($request->leftover_po_no[0])) {
            for ($i = 0; $i < $totalLeftOvers; $i++) {
                $leftOverItem = [
                    'shipping_id' => $shippingId,
                    'po_no' => $request->leftover_po_no[$i],
                    'product_id' => $request->leftover_model_product[$i],
                    'quantity' => $request->leftover_qty[$i]
                ];
                array_push($leftOverItems, $leftOverItem);
            }

            if (!empty($totalLeftOvers)) {
                ShippingLeftOver::insert($leftOverItems);
            }
        }

        // Replacement Parts - Total Child Items
        $totalReplacementParts = count($request->replace_model_product);
        $replacementPartItems = [];
        if ($totalReplacementParts > 0 && !empty($request->replace_model_product[0])) {
            for ($i = 0; $i < $totalReplacementParts; $i++) {
                $replacementPartItem = [
                    'shipping_id' => $shippingId,
                    'product_id' => $request->replace_model_product[$i],
                    'quantity' => $request->replace_qty[$i]
                ];
                array_push($replacementPartItems, $replacementPartItem);
            }
        }

        if(!empty($replacementPartItems)) {
            ShippingReplacementPart::insert($replacementPartItems);
        }

        Helper::logSystemActivity('Shipping', 'Added Shipment successfully');

        // Back to index with success
        return redirect()->route('shippings.show', $shippingId)->with('custom_success', 'Shipment has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shipping = Shipping::with(['items.product', 'jobOrder.jobProducts.product'])->findOrFail($id);

        Helper::logSystemActivity('Shipping', 'View Shipment details id: ' . $id);
        return view('shippings.show', ['shipping' => $shipping]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $QRcode
     * @return \Illuminate\Http\Response
     */
    public function ajaxShippingItemShow($QRcode)
    {
        return ShippingItem::with(['shipping.jobOrder', 'product'])->where('qr_code', $QRcode)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipping $shipping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $shipping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function scanQR()
    {
        Helper::logSystemActivity('Shipping', 'Open Scan QR form');

        $workers = User::get();

        return view('shippings.scan-qr', ['workers'=>$workers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function progressStore(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'qr_code' => 'required|exists:shipping_items,id',
            'worker_id' => 'required|exists:users,id',
            'total_planned_qty' => 'required|numeric'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $shippingItem = ShippingItem::with(['shipping', 'product'])->findOrFail($request->qr_code);

        if($shippingItem === null) {
            return redirect()->back()->with('custom_errors', 'Invalid QR Code')->withInput();
        }

        $shippingId = $shippingItem->shipping->id;
        $shippingItemId = $shippingItem->id;

        $shippingItem->worker_id = $request->worker_id;
        $shippingItem->total_plan_qty = $request->total_planned_qty;
        $shippingItem->save();

        Helper::logSystemActivity('Shipping', 'Shipping progress tracking started for shipping ID:' . $shippingId . ' and Shipping Item ID:' . $shippingItemId);

        // Back to index with success
        return redirect()->route('shippings.process', $shippingItemId)->with('custom_success', 'Shipping Loading Proccess Started');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function process($id)
    {
        Helper::logSystemActivity('Shipping', 'Working on Shipping item ID: ' . $id);

        $shippingItem = ShippingItem::with([
            'shipping.jobOrder',
            'product',
            'worker'
        ])->where('is_ended', 0)->findOrFail($id);

        return view('shippings.process', [
            'shippingItem' => $shippingItem
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processProgresses($id)
    {
        return ShippingItem::with(['progresses'])->findOrFail($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processProgressStart(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'timer_type' => 'required|integer',
            'start_date' => 'required|date_format:Y-m-d H:i:s',
            'shipping_item_id' => 'required|exists:shipping_items,id'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $startDate = $request->start_date;

        $shippingProgress = new ShippingProgress;
        $shippingProgress->shipping_item_id = $request->shipping_item_id;
        $shippingProgress->timer_type = $request->timer_type;
        $shippingProgress->started_at = $startDate;
        $shippingProgress->save();

        Helper::logSystemActivity('Shipping', 'Shipping Progress timer Started successfully');

        return ['status' => true, 'id' => $shippingProgress->id];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processProgressStop(Request $request, $id)
    {
        $shippingProgress = ShippingProgress::where('id', $id)->first();

        // Store the item
        $startDate = $shippingProgress->started_at;
        $endDate = \Carbon\Carbon::Now()->format('Y-m-d H:i:s');

        // Update
        $shippingProgress->ended_at = $endDate;
        $shippingProgress->difference_seconds = \Carbon\Carbon::parse($endDate)->diffInSeconds(\Carbon\Carbon::parse($startDate));
        $shippingProgress->save();

        Helper::logSystemActivity('Shipping', 'Shipping Progress timer stopped successfully');

        return ['status' => true];
    }

    /**
     * Update the specified resource in storage.
     * 
     * END Shipping
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function processEnd(Request $request, $id)
    {
        // Validations
        $validatedData = $request->validate([
            'actual_loaded_qty' => 'required|integer'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $shippingItem = ShippingItem::findOrFail($id);
        // Update the item
        $shippingItem->actual_loaded_qty = $request->actual_loaded_qty;
        $shippingItem->is_ended = 1;
        $shippingItem->save();

        Helper::logSystemActivity('Shipping', 'Shipment Loading process ended for item ID : ' . $id);

        // Back to index with success
        return redirect()->route('shippings.index')->with('custom_success', 'Shipment Loading process ended successfully');
    }

    /**
     * Ajax Search the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxSearchQRCode(Request $request)
    {
        $search = $request->q;

        if (empty($search)) {
            return;
        }

        // Find the Item
        $shippingItem = ShippingItem::with(['shipping.jobOrder', 'product'])->where('qr_code', $search)->where('is_ended', 0)->first();

        $response = [];
        if($shippingItem === null) {
            return $response;
        }

        $response[] = array(
            "id" => $shippingItem->id,
            "text" => "Job Order: ".$shippingItem->shipping->jobOrder->order_no_manual. " Product: " . $shippingItem->product-> model_name ." ". $shippingItem->product->product_name
        );

        return $response;
    }

    /**
     * Ajax Search the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxShippingItemSearch(Request $request)
    {
        $qr_code = $request->qr_code;
        $worker_id = $request->worker_id;

        // Find the items
        $shippingItem = ShippingItem::with([
            'shipping.jobOrder',
            'product',
            'worker'
        ])
        ->where('is_ended', 0)
        ->where('id', $qr_code)
        ->where('worker_id', $worker_id)
        ->get();

        return $shippingItem;
    }
}
