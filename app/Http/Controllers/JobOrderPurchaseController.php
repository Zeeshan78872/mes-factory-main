<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrderPurchaseList;
use App\Models\JobOrderBomList;
use App\Models\Notification;
use App\Models\ProductUnit;
use App\Models\Supplier;
use App\Models\JobOrder;
use App\Models\Product;
use Helper;
use Illuminate\Support\Facades\Redirect;
use DB;

class JobOrderPurchaseController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:job-orders.manage-purchase-order');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Purchase Order List', 'View all Purchase Order List');

        $purchaseOrderLists = JobOrderBomList::with('jobOrder')->where('status', 1)->groupBy(['order_id', 'product_id'])->orderBy('id', 'desc')->get();
        return view('purchase-order-list.index', ['purchaseOrderLists' => $purchaseOrderLists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Helper::logSystemActivity('Purchase Order List', 'Open create Purchase Order List form');

        $units = ProductUnit::get();
        $purchaseItemList =JobOrderBomList::select('products.model_name', 'products.id','products.product_name','products.material','products.category_id')->join('products', 'products.id', '=', 'job_order_bom_lists.item_id')->where('order_id',$request->order_id)->get();

        $suppliers = Supplier::get();

        $orderId = $request->order_id;
        $productId = $request->product_id;

        if (empty($orderId) || empty($productId)) {
            return redirect()->route('job-order.purchase.index')->with('custom_errors', 'Invalid Job Order ID OR Product ID');
        }

        $jobOrder = JobOrder::with(['jobProducts', 'jobProducts.product', 'jobProducts.product.subCategory'])->find($orderId);

        return view('purchase-order-list.job-order-purchase', [
            'purchaseItemList' => $purchaseItemList,
            'units' => $units,
            'jobOrder' => $jobOrder,
            'productId' => $productId,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'purchase_order_id.*' => 'nullable',
            'item_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|numeric',
            'total_quantity.*' => 'required|numeric',
            'overwrite_bom_mapping' => 'nullable',
            'order_id' => 'required|exists:job_orders,id',
            'product_id' => 'required|exists:products,id',
            'length.*' => 'nullable|string|max:255',
            'length_unit.*' => 'nullable|string|max:255',
            'width.*' => 'nullable|string|max:255',
            'width_unit.*' => 'nullable|string|max:255',
            'thick.*' => 'nullable|string|max:255',
            'thick_unit.*' => 'nullable|string|max:255',
            'follow_qty_order_by_bom.*' => 'nullable|string|max:5',
            'bom_order_quantity.*' => 'nullable|numeric',
            'order_length.*' => 'nullable|string|max:255',
            'order_length_unit.*' => 'nullable|string|max:255',
            'order_width.*' => 'nullable|string|max:255',
            'order_width_unit.*' => 'nullable|string|max:255',
            'order_thick.*' => 'nullable|string|max:255',
            'order_thick_unit.*' => 'nullable|string|max:255',
            'stock_card_id.*' => 'nullable|exists:product_stock_cards,id',
            'stock_card_balance_quantity.*' => 'nullable|numeric',
            'order_date.*' => 'nullable|date',
            'po_no.*' => 'nullable|string|max:255',
            'order_quantity.*' => 'nullable|numeric',
            'item_price_per_unit.*' => 'nullable|regex:/^\d{1,13}(\.\d{1,4})?$/',
            'supplier_name.*' => 'nullable|string|max:255',
            'est_delievery_date.*' => 'nullable|date',
            'purchase_remarks.*' => 'nullable|string|max:255',
            'send_to_subcon.*' => 'nullable|string|max:5',
            'subcon_date_out.*' => 'nullable|date',
            'subcon_do_no.*' => 'nullable|string|max:255',
            'subcon_quantity.*' => 'nullable|numeric',
            'subcon_item_price_per_unit.*' => 'nullable|regex:/^\d{1,13}(\.\d{1,4})?$/',
            'subcon_name.*' => 'nullable|string|max:255',
            'subcon_est_delievery_date.*' => 'nullable|date',
            'subcon_description.*' => 'nullable|string|max:255',
            'subcon_remarks.*' => 'nullable|string|max:255',
            'location_receiving.*' => 'nullable|string|max:255',
            'location_produce.*' => 'nullable|string|max:255',
            'location_loading.*' => 'nullable|string|max:255',
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Total Items submitted
        $totalItems = count($request->item_id);

        $jobOrderPurchaseItemsUpdate = [];
        $jobOrderPurchaseItemsInsert = [];
        $nowTime = \Carbon\Carbon::now()->toDateTimeString();
        for ($i = 0; $i < $totalItems; $i++) {
            $purchase_order_id = $request->purchase_order_id[$i];
            $item_id = $request->item_id[$i];
            $quantity = $request->quantity[$i];
            $total_quantity = $request->total_quantity[$i];
            $length = $request->length[$i];
            if (isset($request->length_unit[$i]))
                $length_unit = $request->length_unit[$i];
            $width = $request->width[$i];
            if (isset($request->width_unit[$i]))
                $width_unit = $request->width_unit[$i];
            $thick = $request->thick[$i];
            if (isset($request->thick_unit[$i]))
                $thick_unit = $request->thick_unit[$i];

            $location_receiving = $request->location_receiving[$i] ?? null;
            $location_produce = $request->location_produce[$i] ?? null;
            $location_loading = $request->location_loading[$i] ?? null;

            $follow_qty_order_by_bom = $request->follow_qty_order_by_bom[$i];
            $bom_order_quantity = $request->bom_order_quantity[$i];
            $order_length = $request->order_length[$i];
            $order_length_unit = isset($request->order_length_unit[$i]) ? $request->order_length_unit[$i] : '';
            $order_width = $request->order_width[$i];
            $order_width_unit = isset($request->order_width_unit[$i]) ? $request->order_width_unit[$i] : '';
            $order_thick = $request->order_thick[$i];
            $order_thick_unit = (isset($request->order_thick_unit[$i])) ? $request->order_thick_unit[$i] : '';

            // If Old Stock Used
            $stock_card_id = !empty($request->stock_card_id[$i]) ? $request->stock_card_id[$i] : null;
            $stock_card_balance_quantity = !empty($request->stock_card_balance_quantity[$i]) ? $request->stock_card_balance_quantity[$i] : null;

            // If new Order
            $order_date = $request->order_date[$i];
            $po_no = $request->po_no[$i];
            $order_quantity = $request->order_quantity[$i];
            $item_price_per_unit = $request->item_price_per_unit[$i];
            $supplier_name = $request->supplier_name[$i];
            $est_delievery_date = $request->est_delievery_date[$i];
            $purchase_remarks = $request->purchase_remarks[$i];

            // If SUBCON assignment
            $send_to_subcon = $request->send_to_subcon[$i];
            $subcon_date_out = $request->send_to_subcon[$i] ? $request->subcon_date_out[$i] : null;
            $subcon_do_no = $request->send_to_subcon[$i] ? $request->subcon_do_no[$i] : null;
            $subcon_quantity = $request->send_to_subcon[$i] ? $request->subcon_quantity[$i] : null;
            $subcon_item_price_per_unit = $request->send_to_subcon[$i] ? $request->subcon_item_price_per_unit[$i] : null;
            $subcon_name = $request->send_to_subcon[$i] ? $request->subcon_name[$i] : null;
            $subcon_est_delievery_date = $request->send_to_subcon[$i] ? $request->subcon_est_delievery_date[$i] : null;
            $subcon_description = $request->send_to_subcon[$i] ? $request->subcon_description[$i] : null;
            $subcon_remarks = $request->send_to_subcon[$i] ? $request->subcon_remarks[$i] : null;

            $jobOrderPurchaseData = [
                'id' => $purchase_order_id,
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'item_id' => $item_id,
                'bom_quantity' => $quantity,
                'follow_qty_order_by_bom' => $follow_qty_order_by_bom,
                'bom_total_quantity' => $total_quantity,
                'length' => $length,
//                'length_unit' => $length_unit,
                'width' => $width,
//                'width_unit' => $width_unit,
                'thick' => $thick,
//                'thick_unit' => $thick_unit,
                'bom_order_quantity' => $bom_order_quantity,
                'order_length' => $order_length,
                'order_length_unit' => $order_length_unit,
                'order_width' => $order_width,
                'order_width_unit' => $order_width_unit,
                'order_thick' => $order_thick,
                'order_thick_unit' => $order_thick_unit,
                'stock_card_id' => $stock_card_id,
                'stock_card_balance_quantity' => $stock_card_balance_quantity,
                'order_date' => $order_date,
                'po_no' => $po_no,
                'order_quantity' => $order_quantity,
//                'location_receiving' => $location_receiving,
//                'location_produce' => $location_produce,
//                'location_loading' => $location_loading,
                'item_price_per_unit' => $item_price_per_unit,
                'supplier_name' => $supplier_name,
                'est_delievery_date' => $est_delievery_date,
                'purchase_remarks' => $purchase_remarks,
                'send_to_subcon' => $send_to_subcon,
                'subcon_date_out' => $subcon_date_out,
                'subcon_do_no' => $subcon_do_no,
                'subcon_quantity' => $subcon_quantity,
                'subcon_item_price_per_unit' => $subcon_item_price_per_unit,
                'subcon_name' => $subcon_name,
                'subcon_est_delievery_date' => $subcon_est_delievery_date,
                'subcon_description' => $subcon_description,
                'subcon_remarks' => $subcon_remarks,
                'created_at' => $nowTime,
                'updated_at' => $nowTime
            ];
            if ($purchase_order_id > 0) {
                array_push($jobOrderPurchaseItemsUpdate, $jobOrderPurchaseData);
            } else {
                if ($jobOrderPurchaseData['id'] == 0) {
                    unset($jobOrderPurchaseData['id']);
                    array_push($jobOrderPurchaseItemsInsert, $jobOrderPurchaseData);
                }
            }
        }

        // Notify for purchase order
        $checkPurchaseListCount = JobOrderPurchaseList::where('order_id', $request->order_id)->where('product_id', $request->product_id)->count();

        if ($checkPurchaseListCount === 0) {
            $orderNotifyDetails = JobOrder::where('id', $request->order_id)->first();
            $productNotifyDetails = Product::where('id', $request->product_id)->first();

            // If New Purchase List
            $notification = new Notification;
            $notification->type = 1;
            $notification->title = 'New Purchase List Added';

            $notification->description = 'Purchase List Created By:<a target="_blank" href="' . route('users.show', $request->user()->id) . '">' . $request->user()->name . "</a> For Job Order:" . $orderNotifyDetails->order_no_manual . " and Product:" . $productNotifyDetails->model_name . " " . $productNotifyDetails->product_name;

            $notification->action_link = route('job-order.purchase.index') . '/' . $request->order_id . "/" . $request->product_id;
            $notification->save();
        } else {
            $orderNotifyDetails = JobOrder::where('id', $request->order_id)->first();
            $productNotifyDetails = Product::where('id', $request->product_id)->first();

            // If Old Purchase List Updated
            $notification = new Notification;
            $notification->type = 1;
            $notification->title = 'Updated Purchase List';

            $notification->description = 'Purchase List Updated By:<a target="_blank" href="' . route('users.show', $request->user()->id) . '">' . $request->user()->name . "</a> For Job Order:" . $orderNotifyDetails->order_no_manual . " and Product:" . $productNotifyDetails->model_name . " " . $productNotifyDetails->product_name;

            $notification->action_link = route('job-order.purchase.index') . '/' . $request->order_id . "/" . $request->product_id;
            $notification->save();
        }

        // Save Job Order Purchase List for the Product
        // JobOrderPurchaseList::where('order_id', $request->order_id)->where('product_id', $request->product_id)->delete();

        // Insert if new items
        if (!empty($jobOrderPurchaseItemsInsert)) {
            JobOrderPurchaseList::insert($jobOrderPurchaseItemsInsert);
        }
        // Update if already created before
        if (!empty($jobOrderPurchaseItemsUpdate)) {
            foreach ($jobOrderPurchaseItemsUpdate as $jobPurchaseItem) {
                $jobItem = JobOrderPurchaseList::find($jobPurchaseItem['id']);
                $jobItem->order_id = $jobPurchaseItem['order_id'];
                $jobItem->product_id = $jobPurchaseItem['product_id'];
                $jobItem->item_id = $jobPurchaseItem['item_id'];
                $jobItem->bom_quantity = $jobPurchaseItem['bom_quantity'];
                $jobItem->follow_qty_order_by_bom = $jobPurchaseItem['follow_qty_order_by_bom'];
                $jobItem->bom_total_quantity = $jobPurchaseItem['bom_total_quantity'];
                $jobItem->length = $jobPurchaseItem['length'];
//                $jobItem->length_unit = $jobPurchaseItem['length_unit'];
                $jobItem->width = $jobPurchaseItem['width'];
//                $jobItem->width_unit = $jobPurchaseItem['width_unit'];
                $jobItem->thick = $jobPurchaseItem['thick'];
//                $jobItem->thick_unit = $jobPurchaseItem['thick_unit'];
                $jobItem->bom_order_quantity = $jobPurchaseItem['bom_order_quantity'];
                $jobItem->order_length = $jobPurchaseItem['order_length'];
                $jobItem->order_length_unit = $jobPurchaseItem['order_length_unit'];
                $jobItem->order_width = $jobPurchaseItem['order_width'];
                $jobItem->order_width_unit = $jobPurchaseItem['order_width_unit'];
                $jobItem->order_thick = $jobPurchaseItem['order_thick'];
                $jobItem->order_thick_unit = $jobPurchaseItem['order_thick_unit'];
                $jobItem->stock_card_id = $jobPurchaseItem['stock_card_id'];
                $jobItem->stock_card_balance_quantity = $jobPurchaseItem['stock_card_balance_quantity'];
                $jobItem->order_date = $jobPurchaseItem['order_date'];
                $jobItem->po_no = $jobPurchaseItem['po_no'];
                $jobItem->order_quantity = $jobPurchaseItem['order_quantity'];
//                $jobItem->location_receiving = $jobPurchaseItem['location_receiving'];
//                $jobItem->location_produce = $jobPurchaseItem['location_produce'];
//                $jobItem->location_loading = $jobPurchaseItem['location_loading'];
                $jobItem->item_price_per_unit = $jobPurchaseItem['item_price_per_unit'];
                $jobItem->supplier_name = $jobPurchaseItem['supplier_name'];
                $jobItem->est_delievery_date = $jobPurchaseItem['est_delievery_date'];
                $jobItem->purchase_remarks = $jobPurchaseItem['purchase_remarks'];
                $jobItem->send_to_subcon = $jobPurchaseItem['send_to_subcon'];
                $jobItem->subcon_date_out = $jobPurchaseItem['subcon_date_out'];
                $jobItem->subcon_do_no = $jobPurchaseItem['subcon_do_no'];
                $jobItem->subcon_quantity = $jobPurchaseItem['subcon_quantity'];
                $jobItem->subcon_item_price_per_unit = $jobPurchaseItem['subcon_item_price_per_unit'];
                $jobItem->subcon_name = $jobPurchaseItem['subcon_name'];
                $jobItem->subcon_est_delievery_date = $jobPurchaseItem['subcon_est_delievery_date'];
                $jobItem->subcon_description = $jobPurchaseItem['subcon_description'];
                $jobItem->subcon_remarks = $jobPurchaseItem['subcon_remarks'];
                $jobItem->updated_at = $jobPurchaseItem['updated_at'];
                $jobItem->save();

                $bomList = JobOrderBomList::where([
                    'order_id' => $jobPurchaseItem['id'],
                    'product_id' => $jobPurchaseItem['product_id'],
                    'item_id' => $jobPurchaseItem['item_id'],
                ])->get();
                foreach ($bomList as $key => $_data):
                    $update_bom = JobOrderBomList::find($_data->id);
                    $update_bom->length = $request->order_length_[$key];
//                    $update_bom->length_unit = $request->order_length_unit_[$key];
                    $update_bom->width = $request->order_width_[$key];
//                    $update_bom->width_unit = $request->order_width_unit_[$key];
                    $update_bom->thick = $request->order_thick_[$key];
//                    $update_bom->thick_unit = $request->order_thick_unit_[$key];
                    $update_bom->save();
                endforeach;
            }
        }

        Helper::logSystemActivity('Purchase Order List', 'Add/Update Purchase List for Job Order id:' . $request->order_id . ' And Product id:' . $request->product_id);

        // Back to index with success
//        return redirect()->route('job-order.purchase.index')->with('custom_success', 'Job Order Purchase List has been Added successfully');
        return Redirect::route('job-order.purchase.create', ['order_id' => $request->order_id, 'product_id' => $request->product_id])->with('custom_success', 'Job Order Purchase List has been Added successfully');
    }

    /**
     * Get the Purchase List Of specified Job Order.
     *
     * @param int $order_id
     * @param int $product_id
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseList($order_id, $product_id)
    {
        // Get Job Order Purchase List
        $purchaseList = JobOrderPurchaseList::with(
            [
                'item',
                'item.category',
                'item.subCategory',
                'item.bomCategory',
                'item.parentProduct',
                'stockCard',
                'product',
                'product.category',
                'product.subCategory',
                'product.bomCategory'
            ]
        )
            ->where('order_id', $order_id)
            ->where('product_id', $product_id)
            ->get()
            ->sortByDesc('item.bomCategory.id');

        // Get Job Order BOM List
        $bomItemsList = JobOrderBomList::with(
            [
                'item',
                'item.category',
                'item.subCategory',
                'item.bomCategory',
                'item.parentProduct',
                'product',
                'product.category',
                'product.subCategory',
                'product.bomCategory',
                'product.parentProduct'
            ]
        )
            ->where('status', 1)
            ->where('order_id', $order_id)
            ->where('product_id', $product_id)
            ->get()
            ->sortByDesc('item.bomCategory.id');

        $bomItemsList = $bomItemsList->toArray();

        $tempBomItemsList = [];
        foreach ($bomItemsList as $bomItem) {
            $bomItem['id'] = 0;
            array_push($tempBomItemsList, $bomItem);
        }

        if (!$purchaseList->isEmpty()) {
            $totalBomItems = count($bomItemsList);
            $totalPurchaseItems = count($purchaseList);

            // if($totalBomItems !== $totalPurchaseItems) {
            // $bomItemsList = $bomItemsList->toArray();
            $purchaseList = $purchaseList->toArray();
            // $tempBomItemsList = [];
            // foreach($bomItemsList as $bomItem) {
            //     $bomItem['id'] = 0;
            //     array_push($tempBomItemsList, $bomItem);
            // }
            $finalList = array_merge($purchaseList, $tempBomItemsList);
            $tempArr = [];
            $existingItems = [];
            foreach ($finalList as $listItem) {
                if (!in_array($listItem['item_id'], $existingItems)) {
                    array_push($tempArr, $listItem);
                }
                array_push($existingItems, $listItem['item_id']);
            }
            return $tempArr;
            // }

            // return $purchaseList;
        }

        // Get Job Order BOM List if Purchase Order Not Created Yet
        return $tempBomItemsList;
    }
}
