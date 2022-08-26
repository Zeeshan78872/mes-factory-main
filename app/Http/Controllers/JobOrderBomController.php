<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrderBomList;
use App\Models\ProductBomMapping;
use App\Models\Notification;
use App\Models\ProductUnit;
use App\Models\Product;
use App\Models\JobOrder;
use Helper;

class JobOrderBomController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:job-orders.manage-bom-list');

        $this->middleware('RolePermissionCheck:bom-list.add')->only(['store']);
        $this->middleware('RolePermissionCheck:bom-list.edit')->only(['store']);
        $this->middleware('RolePermissionCheck:bom-list.view')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('BOM List', 'View all BOM List');

        $bomLists = JobOrderBomList::with('jobOrder')->groupBy(['order_id', 'product_id'])->orderBy('id', 'desc')->get();
        return view('bom-list.index', ['bomLists' => $bomLists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Helper::logSystemActivity('BOM List', 'Open create Bom List form');

        $units = ProductUnit::get();

        $orderId = $request->order_id;
        $jobOrder = null;
        if(!empty($orderId)) {
            $jobOrder = JobOrder::with(['jobProducts', 'jobProducts.product.parentProduct', 'jobProducts.product.subCategory'])->find($orderId);
        }

        return view('bom-list.job-order-bom-new', [
            'units' => $units,
            'jobOrder' => $jobOrder,
            'productId' => $request->product_id
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
            'item_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|numeric',
            'total_quantity.*' => 'required|numeric',
            'overwrite_bom_mapping' => 'nullable',
            'order_id' => 'required|exists:job_orders,id',
            'product_id' => 'required|exists:products,id',
            'status.*' => 'nullable|string|max:5',
            'is_change.*' => 'nullable|string|max:5',
            'order_size_same_as_bom.*' => 'nullable|string|max:5',
            'length.*' => 'nullable|string|max:255',
            'length_unit.*' => 'nullable|string|max:255',
            'width.*' => 'nullable|string|max:255',
            'width_unit.*' => 'nullable|string|max:255',
            'thick.*' => 'nullable|string|max:255',
            'thick_unit.*' => 'nullable|string|max:255',
            'order_quantity.*' => 'nullable|numeric',
            'order_length.*' => 'nullable|string|max:255',
            'order_length_unit.*' => 'nullable|string|max:255',
            'order_width.*' => 'nullable|string|max:255',
            'order_width_unit.*' => 'nullable|string|max:255',
            'order_thick.*' => 'nullable|string|max:255',
            'order_thick_unit.*' => 'nullable|string|max:255',
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

        $jobOrderBomItems = [];
        $bomItemsMapping = [];
        $changedBomItems = [];
        $nowTime = \Carbon\Carbon::now()->toDateTimeString();

        for ($i = 0; $i < $totalItems; $i++) {
            $item_id = $request->item_id[$i];
            $quantity = $request->quantity[$i];
            $total_quantity = $request->total_quantity[$i];

            $order_size_same_as_bom = $request->order_size_same_as_bom[$i];
            $length = $request->length[$i] ?? null;
            $length_unit = $request->length_unit[$i] ?? null;
            $width = $request->width[$i] ?? null;
            $width_unit = $request->width_unit[$i] ?? null;
            $thick = $request->thick[$i] ?? null;
            $thick_unit = $request->thick_unit[$i] ?? null;

            $remarks = $request->remarks[$i] ?? null;

            $location_receiving = $request->location_receiving[$i] ?? null;
            $location_produce = $request->location_produce[$i] ?? null;
            $location_loading = $request->location_loading[$i] ?? null;

            $order_quantity = $request->order_quantity[$i] ?? null;
            $order_length = $request->order_length[$i] ?? null;
            $order_length_unit = $request->order_length_unit[$i] ?? null;
            $order_width = $request->order_width[$i] ?? null;
            $order_width_unit = $request->order_width_unit[$i] ?? null;
            $order_thick = $request->order_thick[$i] ?? null;
            $order_thick_unit = $request->order_thick_unit[$i] ?? null;

            $status = null;
            if(isset($request->status[$i]) && $request->status[$i] == 1) {
                $status = $request->status[$i];
            }

            // Get details for code generation
            $jobOrderDetails = JobOrder::find($request->order_id);
            $productDetails = Product::find($request->product_id);
            $itemDetails = Product::find($item_id);

            // Example Code: [jobOrder-colourCode-modelCode-size-itemCode/subitemCod]
            $customCodeGenerated = str_replace(' ', '',
                strtoupper(
                    $jobOrderDetails->order_no_manual.'-'.
                    $productDetails->color_code.'-'.
                    $productDetails->model_name.'-'.
                    $productDetails->length.'x'.
                    $productDetails->width.'x'.
                    $productDetails->thick.'-'.
                    $itemDetails->model_name
                )
            );

            $jobOrderBomData = [
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'item_id' => $item_id,
                'quantity' => $quantity,
                'total_quantity' => $total_quantity,
                'order_size_same_as_bom' => $order_size_same_as_bom,
                'code_generated' => $customCodeGenerated,
                'status' => $status,
                'length' => $length,
                'length_unit' => $length_unit,
                'width' => $width,
                'width_unit' => $width_unit,
                'thick' => $thick,
                'thick_unit' => $thick_unit,
                'order_quantity' => $order_quantity,
                'order_length' => $order_length,
                'order_length_unit' => $order_length_unit,
                'order_width' => $order_width,
                'order_width_unit' => $order_width_unit,
                'order_thick' => $order_thick,
                'order_thick_unit' => $order_thick_unit,
                'location_receiving' => $location_receiving,
                'location_produce' => $location_produce,
                'location_loading' => $location_loading,
                'remarks' => $remarks,
                'created_at' => $nowTime,
                'updated_at' => $nowTime
            ];
            array_push($jobOrderBomItems, $jobOrderBomData);

            if (isset($request->is_change[$i]) && $request->is_change[$i] == 1) {
                array_push($changedBomItems, $jobOrderBomData);
            }

            // If Overwrite BOM Mapping
            if(isset($request->overwrite_bom_mapping) && $request->overwrite_bom_mapping == 1) {
                $mapData = [
                    'product_id' => $request->product_id,
                    'item_id' => $item_id,
                    'quantity' => $quantity,
                    'created_at' => $nowTime,
                    'updated_at' => $nowTime
                ];
                array_push($bomItemsMapping, $mapData);

                if(isset($request->combine_models_bom_hidden) && $request->combine_models_bom_hidden) {
                    $productDetails = Product::where('id', $request->product_id)->first();
                    $jobOrder = JobOrder::with(['jobProducts.product'])->find($request->order_id);
                    $jobProducts = $jobOrder->jobProducts;

                    foreach($jobProducts as $product) {
                        if(($productDetails->model_name == $product->product->model_name) && $request->product_id != $product->product->id) {
                            $mapData = [
                                'product_id' => $product->product_id,
                                'item_id' => $item_id,
                                'quantity' => $quantity,
                                'created_at' => $nowTime,
                                'updated_at' => $nowTime
                            ];
                            array_push($bomItemsMapping, $mapData);
                        }
                    }
                }
            }
        }

        // Save Job Order BOM List for the Product
        $checkBOMListCount = JobOrderBomList::where('order_id', $request->order_id)->where('product_id', $request->product_id)->count();
        
        if($checkBOMListCount === 0) {
            $orderNotifyDetails = JobOrder::where('id', $request->order_id)->first();
            $productNotifyDetails = Product::where('id', $request->product_id)->first();
            // If New BOM List
            $notification = new Notification;
            $notification->type = 1;
            $notification->title = 'New BOM List Added';

            $notification->description = 'BOM List Created By:<a target="_blank" href="' . route('users.show', $request->user()->id) . '">' . $request->user()->name . "</a> For Job Order:". $orderNotifyDetails->order_no_manual." and Product:". $productNotifyDetails->model_name . " " . $productNotifyDetails->product_name;

            $notification->action_link = route('job-order.bom.index') . '/' . $request->order_id . "/" . $request->product_id;
            $notification->save();
        }

        if(!empty($changedBomItems)) {
            foreach($changedBomItems as $changedItem) {
                $orderNotifyDetails = JobOrder::where('id', $request->order_id)->first();
                $productNotifyDetails = Product::where('id', $request->product_id)->first();
                $itemNotifyDetails = Product::where('id', $changedItem['item_id'])->first();
                // If New BOM List
                $notification = new Notification;
                $notification->type = 1;
                $notification->title = 'Change In BOM List';

                $notification->description = 'BOM List Changed By:<a target="_blank" href="' . route('users.show', $request->user()->id) . '">' . $request->user()->name . "</a> For Job Order:" . $orderNotifyDetails->order_no_manual . " and Product:" . $productNotifyDetails->model_name . " " . $productNotifyDetails->product_name . " and item changed: " . $itemNotifyDetails->model_name . " " . $itemNotifyDetails->product_name;
                
                $notification->action_link = route('job-order.bom.index') . '/' . $request->order_id . "/" . $request->product_id;
                $notification->save();

            }
        }

        // Save Job Order BOM List for the Product
        JobOrderBomList::where('order_id', $request->order_id)->where('product_id', $request->product_id)->delete();
        JobOrderBomList::insert($jobOrderBomItems);

        // Update Job Order
        $jobOrderDetails = JobOrder::find($request->order_id);
        $jobOrderDetails->combine_models_bom = $request->combine_models_bom_hidden;
        $jobOrderDetails->save();

        // Overwrite Old BOM Mappings for the Product
        if(!empty($bomItemsMapping)) {
            // Delete All Old Mappings
            ProductBomMapping::where('product_id', $request->product_id)->delete();
            // Save New Mappings
            ProductBomMapping::insert($bomItemsMapping);

            Helper::logSystemActivity('Products', 'Update Old BOM Mapping with new From Job Order BOM for product id:');

        }

        Helper::logSystemActivity('Products', 'Add BOM List for Job Order id:' . $request->order_id . ' And Product id:'. $request->product_id);

        // Back to index with success
        return redirect()->route('job-order.bom.index')->with('custom_success', 'Job Order BOM List has been Added successfully');
    }

    /**
     * Get the Bom List Of specified Job Order.
     *
     * @param  int  $order_id
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function getBomList($order_id, $product_id)
    {
        // Get Job Order BOM List
        $bomList = JobOrderBomList::with(
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
        ->where('order_id', $order_id)
        ->where('product_id', $product_id)
        ->orderBy('id', 'asc')
        ->get();

        if($bomList->isEmpty()) {
            // Get Product BOM List In case the Job Order BOM Is not created Yet
            $bomList = ProductBomMapping::with(
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
            )->where('product_id', $product_id)->get()->sortByDesc('item.bomCategory.id');
        }

        return $bomList;
    }
}
