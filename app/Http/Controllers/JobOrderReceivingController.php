<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrderReceivingList;
use App\Models\JobOrderPurchaseList;
use App\Models\Notification;
use App\Models\ProductUnit;
use App\Models\JobOrder;
use App\Models\InventoryAudit;
use App\Models\ProductStockCard;
use App\Models\ProductStock;
use App\Models\ProductionIssuedItem;
use App\Models\Product;
use DB;

use Helper;

class JobOrderReceivingController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:job-orders.manage-receiving-order');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Receiving Order List', 'View all Receiving Order List');

        $receivingOrderLists = JobOrderReceivingList::with('jobOrder')->groupBy(['order_id', 'product_id'])->orderBy('id', 'desc')->get();
        return view('receiving-order-list.index', ['receivingOrderLists' => $receivingOrderLists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Helper::logSystemActivity('Receiving Order List', 'Open create Receiving Order List form');

        $units = ProductUnit::get();

        $orderId = $request->order_id;
        $productId = $request->product_id;
        $jobOrder = null;

        if (!empty($orderId)) {
            $jobOrder = JobOrder::with(['jobProducts', 'jobProducts.product', 'jobProducts.product.subCategory'])->find($orderId);
        }

        return view('receiving-order-list.job-order-receiving', [
            'units' => $units,
            'jobOrder' => $jobOrder,
            'orderId' => $orderId,
            'productId' => $productId
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
            'order_id.*' => 'required|exists:orders,id',
            'product_id.*' => 'required|exists:products,id',
            'item_id.*' => 'required|exists:products,id',
            'purchase_id.*' => 'required|exists:job_order_purchase_lists,id',
            'date_in.*' => 'required|date',
            'do_no.*' => 'nullable|string|max:255',
            'ordered_quantity.*' => 'required|numeric',
            'bom_order_quantity.*' => 'nullable|numeric',
            'received_quantity.*' => 'required|numeric',
            'extra_less.*' => 'required|numeric',
            'balance.*' => 'required|numeric',
            'balance_received_as_well.*' => 'nullable|string|max:5',
            'receiving_remarks.*' => 'nullable|string|max:255',
            'receive_as_well.*' => 'required|string|max:5',
            'receiving_remarks_s.*' => 'nullable|string|max:255',
            'send_to_reject.*' => 'required|string|max:5',
            'reject_date_out.*' => 'nullable|date',
            'reject_memo_no.*' => 'nullable|string|max:255',
            'reject_quantity.*' => 'nullable|numeric',
            'reject_est_delievery_date.*' => 'nullable|date',
            'reject_receive_as_well.*' => 'string|max:5',
            'reject_cause.*' => 'nullable|string|max:255',
            'reject_remarks.*' => 'nullable|string|max:255',
            'location_receiving.*' => 'nullable|string|max:255',
            'location_produce.*' => 'nullable|string|max:255',
            'location_loading.*' => 'nullable|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Total Items submitted
        $totalItems = count($request->purchase_id);

        $jobOrderReceivingItems = [];
        $jobOrderReceivingItemsWithPics = [];
        $nowTime = \Carbon\Carbon::now()->toDateTimeString();

        for ($i = 0; $i < $totalItems; $i++) {
            $item_id = $request->item_id[$i];
            $purchase_id = $request->purchase_id[$i];
            
            $date_in = $request->date_in[$i];
            $do_no = $request->do_no[$i];
            $received_quantity = $request->received_quantity[$i];
            $ordered_quantity = $request->ordered_quantity[$i];
            $bom_order_quantity = $request->bom_order_quantity[$i];
            $extra_less = $request->extra_less[$i];
            $balance = $request->balance[$i];
            $balance_received_as_well = $request->balance_received_as_well[$i] ? $request->balance_received_as_well[$i] : null;

            $location_receiving = $request->location_receiving[$i] ?? null;
            $location_produce = $request->location_produce[$i] ?? null;
            $location_loading = $request->location_loading[$i] ?? null;

            // If balance Received as well\
            if(!empty($balance_received_as_well) && !empty($balance)) {
                $maxId = ProductStockCard::max('id');
                $newId = $maxId + 1;
                // Create Stock Card
                $checkBalanceProductStockCard = ProductStockCard::where('product_id', $item_id)->where('order_id', $request->order_id)->where('is_rejected', 0)->where('is_balance', 1);
                $issuedStock = true;
                if ($checkBalanceProductStockCard->exists()) {
                    $checkIssuedStockCard = ProductionIssuedItem::where('stock_card_id', $checkBalanceProductStockCard->first()->id);

                    if (!$checkIssuedStockCard->exists()) {
                        $issuedStock = false;

                        ProductStockCard::updateOrCreate(
                            [
                                'product_id' => $item_id,
                                'order_id' => $request->order_id,
                                'is_rejected' => 0,
                                'is_balance' => 1
                            ],
                            [
                                'ordered_quantity' => $bom_order_quantity,
                                'available_quantity' => $balance,
                                'order_id' => $request->order_id,
                                'job_product_id' => $request->product_id,
                                'product_id' => $item_id,
                                'date_in' => $date_in,
                                'is_rejected' => 0,
                                'is_balance' => 1
                            ]
                        );
                    }
                } else {
                    $issuedStock = false;

                    ProductStockCard::updateOrCreate(
                        [
                            'product_id' => $item_id,
                            'order_id' => $request->order_id,
                            'is_rejected' => 0,
                            'is_balance' => 1
                        ],
                        [
                            'stock_card_number' => date("Y") . '/' . $newId,
                            'ordered_quantity' => $bom_order_quantity,
                            'available_quantity' => $balance,
                            'order_id' => $request->order_id,
                            'job_product_id' => $request->product_id,
                            'product_id' => $item_id,
                            'date_in' => $date_in,
                            'is_rejected' => 0,
                            'is_balance' => 1
                        ]
                    );
                }

                if(!$issuedStock) {
                    $reject_quantity = $request->reject_quantity[$i] ? $request->reject_quantity[$i] : null;

                    // Update Product Stock
                    $productStock = ProductStock::where('product_id', '=', $item_id)->first();
                    if (empty($productStock)) {
                        $productStock = new ProductStock;
                        $productStock->product_id = $item_id;
                        $productStock->quantity = $balance;
                    } else {
                        $productStock->quantity = $productStock->quantity + $balance;
                    }
                    $productStock->save();

                    // Update Inventory Audit Report
                    $stockCard = ProductStockCard::where('product_id', $item_id)
                        ->where('order_id', $request->order_id)
                        ->where('date_in', $date_in)
                        ->where('is_rejected', 0)
                        ->where('is_balance', 1)
                        ->first();
                    // Inventory Audit Logs
                    $inventoryAudit = new InventoryAudit;
                    $inventoryAudit->stock_card_id = $stockCard->id;
                    $inventoryAudit->quantity = $balance;
                    $inventoryAudit->movement_type = 1;
                    $inventoryAudit->save();
                }
            }
            
            $receiving_remarks = $request->receiving_remarks[$i] ? $request->receiving_remarks[$i] : null;
            $receive_as_well = $request->receive_as_well[$i] ? $request->receive_as_well[$i] : null;
            $receiving_remarks_s = $request->receiving_remarks_s[$i] ? $request->receiving_remarks_s[$i] : null;
            
            $send_to_reject = $request->send_to_reject[$i] ? $request->send_to_reject[$i] : null;

            if(!empty($send_to_reject)) {
                $is_rejected = true;

                $reject_date_out = $request->reject_date_out[$i] ? $request->reject_date_out[$i] : null;
                $reject_memo_no = $request->reject_memo_no[$i] ? $request->reject_memo_no[$i] : null;
                $reject_quantity = $request->reject_quantity[$i] ? $request->reject_quantity[$i] : null;
                $reject_est_delievery_date = $request->reject_est_delievery_date[$i] ? $request->reject_est_delievery_date[$i] : null;
                $reject_receive_as_well = $request->reject_receive_as_well[$i] ? $request->reject_receive_as_well[$i] : null;
                $reject_cause = $request->reject_cause[$i] ? $request->reject_cause[$i] : null;
                $reject_remarks = $request->reject_remarks[$i] ? $request->reject_remarks[$i] : null;

                if(!empty($reject_receive_as_well) && !empty($reject_quantity)) {
                    $maxId = ProductStockCard::max('id');
                    $newId = $maxId + 1;
                    // Create Stock Card
                    $checkRejectedProductStockCard = ProductStockCard::where('product_id', $item_id)->where('order_id', $request->order_id)->where('is_rejected', 1)->where('is_balance', 0);
                    $issuedStock = true;
                    if ($checkRejectedProductStockCard->exists()) {
                        $checkIssuedStockCard = ProductionIssuedItem::where('stock_card_id', $checkRejectedProductStockCard->first()->id);
                        if (!$checkIssuedStockCard->exists()) {
                            $issuedStock = false;

                            ProductStockCard::updateOrCreate(
                                [
                                    'product_id' => $item_id,
                                    'order_id' => $request->order_id,
                                    'is_rejected' => 1,
                                    'is_balance' => 0
                                ],
                                [
                                    'ordered_quantity' => $bom_order_quantity,
                                    'available_quantity' => $reject_quantity,
                                    'order_id' => $request->order_id,
                                    'job_product_id' => $request->product_id,
                                    'product_id' => $item_id,
                                    'date_in' => $date_in,
                                    'date_out' => $reject_date_out,
                                    'is_rejected' => 1,
                                    'is_balance' => 0
                                ]
                            );
                        }
                    } else {
                        $issuedStock = false;

                        ProductStockCard::updateOrCreate(
                            [
                                'product_id' => $item_id,
                                'order_id' => $request->order_id,
                                'is_rejected' => 1,
                                'is_balance' => 0
                            ],
                            [
                                'stock_card_number' => date("Y") . '/' . $newId,
                                'ordered_quantity' => $bom_order_quantity,
                                'available_quantity' => $reject_quantity,
                                'order_id' => $request->order_id,
                                'job_product_id' => $request->product_id,
                                'product_id' => $item_id,
                                'date_in' => $date_in,
                                'date_out' => $reject_date_out,
                                'is_rejected' => 1,
                                'is_balance' => 0
                            ]
                        );
                    }

                    if (!$issuedStock) {
                        // Update Product Stock
                        $productStock = ProductStock::where('product_id', '=', $item_id)->first();
                        if (empty($productStock)) {
                            $productStock = new ProductStock;
                            $productStock->product_id = $item_id;
                            $productStock->quantity = $reject_quantity;
                        } else {
                            $productStock->quantity = $productStock->quantity + $reject_quantity;
                        }
                        $productStock->save();

                        // Update Inventory Audit Report
                        $stockCard = ProductStockCard::where('product_id', $item_id)
                        ->where('order_id', $request->order_id)
                            ->where('date_in', $date_in)
                            ->where('is_rejected', 1)
                            ->where('is_balance', 0)
                            ->first();
                        // Inventory Audit Logs
                        $inventoryAudit = new InventoryAudit;
                        $inventoryAudit->stock_card_id = $stockCard->id;
                        $inventoryAudit->quantity = $received_quantity + $extra_less - $reject_quantity;
                        $inventoryAudit->movement_type = 1;
                        $inventoryAudit->save();
                    }
                }

                if (isset($request->file('reject_picture_link')[$i]) && !empty($request->file('reject_picture_link')[$i])) {
                    $file = $request->file('reject_picture_link')[$i];
                    $reject_picture_link = $file->store('recieving_reject_picture_link');
                } else {
                    $reject_picture_link = null;
                }
            } else {
                $reject_date_out = $reject_memo_no = $reject_quantity = $reject_est_delievery_date = $reject_receive_as_well = $reject_cause = $reject_picture_link = $reject_remarks = null;
                $is_rejected = false;
            }

            // Create Stock Card
            $maxId = ProductStockCard::max('id');
            $newId = $maxId + 1;
            $checkProductStockCard = ProductStockCard::where('product_id', $item_id)->where('order_id', $request->order_id)->where('is_rejected', 0)->where('is_balance', 0);
            $reject_quantity = empty($reject_quantity) ? 0 : $reject_quantity;
            $issuedStock = true;
            if(!empty($bom_order_quantity) && !empty($received_quantity)) {
                if ($checkProductStockCard->exists()) {
                    $checkIssuedStockCard = ProductionIssuedItem::where('stock_card_id', $checkProductStockCard->first()->id);

                    if (!$checkIssuedStockCard->exists()) {
                        $issuedStock = false;
                        ProductStockCard::updateOrCreate(
                            [
                                'product_id' => $item_id,
                                'order_id' => $request->order_id,
                                'is_rejected' => 0,
                                'is_balance' => 0
                            ],
                            [
                                'ordered_quantity' => $bom_order_quantity,
                                'available_quantity' => $received_quantity + $extra_less - $reject_quantity,
                                'order_id' => $request->order_id,
                                'job_product_id' => $request->product_id,
                                'product_id' => $item_id,
                                'date_in' => $date_in,
                                'is_rejected' => 0,
                                'is_balance' => 0
                            ]
                        );
                    }
                } else {
                    $issuedStock = false;

                    ProductStockCard::updateOrCreate(
                        [
                            'product_id' => $item_id,
                            'order_id' => $request->order_id,
                            'is_rejected' => 0,
                            'is_balance' => 0
                        ],
                        [
                            'stock_card_number' => date("Y") . '/' . $newId,
                            'ordered_quantity' => $bom_order_quantity,
                            'available_quantity' => $received_quantity + $extra_less - $reject_quantity,
                            'order_id' => $request->order_id,
                            'job_product_id' => $request->product_id,
                            'product_id' => $item_id,
                            'date_in' => $date_in,
                            'is_rejected' => 0,
                            'is_balance' => 0
                        ]
                    );
                }
                if (!$issuedStock) {
                    // Update Product Stock
                    $productStock = ProductStock::where('product_id', '=', $item_id)->first();
                    if (empty($productStock)) {
                        $productStock = new ProductStock;
                        $productStock->product_id = $item_id;
                        $productStock->quantity = $received_quantity + $extra_less - $reject_quantity;
                    } else {
                        $productStock->quantity = $productStock->quantity + $received_quantity + $extra_less - $reject_quantity;
                    }
                    $productStock->save();

                    // Update Inventory Audit Report
                    $stockCard = ProductStockCard::where('product_id', $item_id)
                    ->where('order_id', $request->order_id)
                        ->where('date_in', $date_in)
                        ->where('is_rejected', 0)
                        ->where('is_balance', 0)
                        ->first();
                    // Inventory Audit Logs
                    $inventoryAudit = new InventoryAudit;
                    $inventoryAudit->stock_card_id = $stockCard->id;
                    $inventoryAudit->quantity = $received_quantity + $extra_less - $reject_quantity;
                    $inventoryAudit->movement_type = 1;
                    $inventoryAudit->save();
                }
            }
            
            // Create Receiving Data
            $jobOrderReceivingData = [
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'purchase_id' => $purchase_id,
                'date_in' => $date_in,
                'do_no' => $do_no,
                'received_quantity' => $received_quantity,
                'extra_less' => $extra_less,
                'balance' => $balance,
                'balance_received_as_well' => $balance_received_as_well,
                'receiving_remarks' => $receiving_remarks,
                'received_as_well' => $receive_as_well,
                'receiving_remarks_s' => $receiving_remarks_s,
                'send_to_reject' => $send_to_reject,
                'reject_date_out' => $reject_date_out,
                'reject_memo_no' => $reject_memo_no,
                'reject_quantity' => $reject_quantity,
                'reject_est_delievery_date' => $reject_est_delievery_date,
                'reject_receive_as_well' => $reject_receive_as_well,
                'reject_cause' => $reject_cause,
                'reject_remarks' => $reject_remarks,
                'location_receiving' => $location_receiving,
                'location_produce' => $location_produce,
                'location_loading' => $location_loading,
                'created_at' => $nowTime,
                'updated_at' => $nowTime
            ];

            if(!empty($reject_picture_link)) {
                $jobOrderReceivingData['reject_picture_link'] = $reject_picture_link;
                array_push($jobOrderReceivingItemsWithPics, $jobOrderReceivingData);
            } else {
                array_push($jobOrderReceivingItems, $jobOrderReceivingData);
            }
        }

        // Save Job Order Receiving List for the Product
        $checkReceivingListCount = JobOrderReceivingList::where('order_id', $request->order_id)->where('product_id', $request->product_id)->count();

        if ($checkReceivingListCount === 0) {
            $orderNotifyDetails = JobOrder::where('id', $request->order_id)->first();
            $productNotifyDetails = Product::where('id', $request->product_id)->first();

            // If New Receiving List
            $notification = new Notification;
            $notification->type = 1;
            $notification->title = 'New Receiving List Added';

            $notification->description = 'Receiving List Created By:<a target="_blank" href="' . route('users.show', $request->user()->id) . '">' . $request->user()->name . "</a> For Job Order:" . $orderNotifyDetails->order_no_manual . " and Product:" . $productNotifyDetails->model_name . " " . $productNotifyDetails->product_name;

            $notification->action_link = route('job-order.receiving.index') . '/' . $request->order_id . "/" . $request->product_id;
            $notification->save();
        } else {
            $orderNotifyDetails = JobOrder::where('id', $request->order_id)->first();
            $productNotifyDetails = Product::where('id', $request->product_id)->first();

            // If Old Receiving List Updated
            $notification = new Notification;
            $notification->type = 1;
            $notification->title = 'Updated Receiving List';

            $notification->description = 'Receiving List Updated By:<a target="_blank" href="' . route('users.show', $request->user()->id) . '">' . $request->user()->name . "</a> For Job Order:" . $orderNotifyDetails->order_no_manual . " and Product:" . $productNotifyDetails->model_name . " " . $productNotifyDetails->product_name;

            $notification->action_link = route('job-order.receiving.index') . '/' . $request->order_id . "/" . $request->product_id;
            $notification->save();
        }

        // Save Job Order Receiving List for the Product
        JobOrderReceivingList::where('order_id', $request->order_id)->where('product_id', $request->product_id)->delete();
        JobOrderReceivingList::insert($jobOrderReceivingItems);
        if(!empty($jobOrderReceivingItemsWithPics)) {
            JobOrderReceivingList::insert($jobOrderReceivingItemsWithPics);
        }

        Helper::logSystemActivity('Receiving Order List', 'Add Receiving List for Job Order id:' . $request->order_id . ' And Product id:' . $request->product_id);

        // Back to index with success
        return redirect()->route('job-order.receiving.index')->with('custom_success', 'Job Order Receiving List has been Added successfully');
    }

    /**
     * Get the Receiving List Of specified Job Order.
     *
     * @param  int  $order_id
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function getReceivingList($order_id, $product_id)
    {
        // Get Job Order Receiving List
        $receivingList = JobOrderReceivingList::with(
                [
                    'purchase',
                    'purchase.item',
                    'purchase.item.category',
                    'purchase.item.subCategory',
                    'purchase.item.bomCategory',
                    'purchase.item.parentProduct',
                    'purchase.product',
                    'purchase.product.category',
                    'purchase.product.subCategory',
                    'purchase.product.bomCategory'
                ]
            )
            ->where('order_id', $order_id)
            ->where('product_id', $product_id)
            ->get()
            ->sortByDesc('item.bomCategory.id');


        if (!$receivingList->isEmpty()) {
            $temp = DB::select('select job_order_purchase_lists.item_id,sum(received_quantity) as received_quantity from job_order_receiving_lists 
            inner join job_order_purchase_lists on job_order_receiving_lists.purchase_id = job_order_purchase_lists.id
            group by job_order_purchase_lists.item_id order by job_order_receiving_lists.product_id desc');
            $data['temp'] = $temp;
            $data['receivingList'] = $receivingList;
            return $data;
        }
        return null;
        // Get Job Order BOM List if Receiving Order Not Created Yet
        // return JobOrderPurchaseList::with(
        //         [
        //             'item',
        //             'item.category',
        //             'item.subCategory',
        //             'item.bomCategory',
        //             'item.parentProduct',
        //             'product',
        //             'product.category',
        //             'product.subCategory',
        //             'product.bomCategory'
        //         ]
        //     )
        //     ->where('order_id', $order_id)
        //     ->where('product_id', $product_id)
        //     ->groupBy('item_id')
        //     ->get()
        //     ->sortByDesc('item.bomCategory.id');
    }



    /**
     * Get the Receiving List Of specified Job Order.
     *
     * @param  int  $order_id
     * @param  int  $product_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stockCardPrint($order_id, $product_id, Request $request)
    {
        $date_in = $request->date_in;
        $is_rejected = $request->is_rejected ?? 0;
        $is_balance = $request->is_balance ?? 0;
        
        $jobPurchaseItem = JobOrderPurchaseList::where('order_id', $order_id)->where('item_id', $product_id)->first();

        $stockCard = ProductStockCard::with('jobOrder', 'product')
            ->when(!empty($order_id), function ($query) use ($order_id) {
                return $query->where('order_id', '=', $order_id);
            })
            ->where('product_id', $product_id)
            ->where('is_rejected', $is_rejected)
            ->where('is_balance', $is_balance)
            ->where('date_in', $date_in)
            ->get();

        if($stockCard->isEmpty()) {
            return "No Stock Card Available";
        }

        return view('receiving-order-list.stock-card-print', [
            'stockCard' => $stockCard[0],
            'jobPurchaseItem' => $jobPurchaseItem
        ]);
    }

    public function getProductTable(){
        $purchaseItemList =
        DB::select(' select products.id,product_categories.name as category,material,
        model_name,product_name 
        from job_order_bom_lists 
        join products on job_order_bom_lists.item_id = products.id join product_categories on product_categories.id = products.category_id 
        group by job_order_bom_lists.product_id');

            return $purchaseItemList;
    }

    public function getProductDetails(Request $request){
        return JobOrderPurchaseList::with(
                    [
                        'item',
                        'item.category',
                        'item.subCategory',
                        'item.bomCategory',
                        'item.parentProduct',
                        'product',
                        'product.category',
                        'product.subCategory',
                        'product.bomCategory'
                    ]
                )
                ->where('order_id', $request->orderId)
                ->where('product_id', $request->pid)
                ->whereIn('item_id',explode(',',$request->list))
                ->groupBy('item_id')
                ->get()
                ->sortByDesc('item.bomCategory.id');
    }
}