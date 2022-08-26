<?php

namespace App\Http\Controllers;

use App\Models\JobOrderReceivingList;
use Illuminate\Http\Request;
use App\Models\MultiSite;
use App\Models\JobOrder;
use App\Models\Notification;
use App\Models\JobOrderProduct;
use App\Models\JobOrderProductPackingPicture;
use App\Models\ProductPackingPicture;
use App\Models\ProductPacking;
use Illuminate\Support\Facades\Storage;
use Helper;
use PDF;
class JobOrderController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check JobOrder Permissions
        $this->middleware('RolePermissionCheck:job-orders.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:job-orders.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:job-orders.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:job-orders.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Job Orders', 'View all job orders list');

        $jobOrders = JobOrder::with(['customer', 'site', 'createdBy', 'updatedBy'])->get();
        // dd($jobOrders);
        return view('job-orders.index', ['jobOrders' => $jobOrders]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Job Orders', 'Open create job order form');

        $multiSites = MultiSite::all();

        return view('job-orders.create', ['multiSites' => $multiSites]);
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
            'order_no_manual' => 'nullable|string|max:255',
            'po_no' => 'nullable|string|max:255',
            'qc_date' => 'nullable|date',
            'crd_date' => 'nullable|date',
            'container_vol' => 'nullable|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'site_id' => 'required|exists:sites,id',
            'product_ids.*' => 'required|exists:products,id'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $jobOrder = new JobOrder;
        $jobOrder->order_no_manual = $request->order_no_manual;

        if (!empty($request->same_qc_date)) {
            $jobOrder->qc_date = $request->qc_date;
        } else {
            $jobOrder->qc_date = null;
        }
        if (!empty($request->same_crd_date)) {
            $jobOrder->crd_date = $request->crd_date;
            $jobOrder->truck_in = $request->truck_in;
        } else {
            $jobOrder->crd_date = null;
            $jobOrder->truck_in = null;
        }
        if (!empty($request->same_container_vol)) {
            $jobOrder->container_vol = $request->container_vol;
        } else {
            $jobOrder->container_vol = null;
        }
        if (!empty($request->same_po_no)) {
            $jobOrder->po_no = $request->po_no;
        } else {
            $jobOrder->po_no = null;
        }

        $jobOrder->customer_id = $request->customer_id;
        $jobOrder->site_id = $request->site_id;
        $jobOrder->created_by = $request->user()->id;
        $jobOrder->updated_by = $request->user()->id;
        $jobOrder->save();

        $jobOrderId = $jobOrder->id;

        // Add Products
        if (!empty($request->product_ids)) {
            $totalProducts = count($request->product_ids);

            for ($i = 0; $i < $totalProducts; $i++) {
                if (empty($request->product_ids[$i])) {
                    continue;
                }

                $jobOrderProduct = new JobOrderProduct;
                $jobOrderProduct->order_id = $jobOrderId;
                $jobOrderProduct->product_id = $request->product_ids[$i];
                $jobOrderProduct->quantity = $request->quantity[$i];
                $jobOrderProduct->product_test = $request->product_test[$i] ?? 0;
                $jobOrderProduct->product_test_remarks = $request->product_test_remarks[$i];
                $jobOrderProduct->remarks = $request->product_remarks[$i];
                $jobOrderProduct->product_packing = $request->product_packing[$i];

                if (empty($request->same_qc_date)) {
                    $jobOrderProduct->qc_date = $request->product_qc_date[$i];
                } else {
                    $jobOrderProduct->qc_date = $request->qc_date;
                }
                if (empty($request->same_crd_date)) {
                    $jobOrderProduct->crd_date = $request->product_crd_date[$i];
                    $jobOrderProduct->truck_in = $request->product_truck_in[$i];
                } else {
                    $jobOrderProduct->crd_date = $request->crd_date;
                    $jobOrderProduct->truck_in = $request->truck_in;
                }
                if (empty($request->same_container_vol)) {
                    $jobOrderProduct->container_vol = $request->product_container_vol[$i];
                } else {
                    $jobOrderProduct->container_vol = $request->container_vol;
                }
                if (empty($request->same_po_no)) {
                    $jobOrderProduct->po_no = $request->product_po_no[$i];
                } else {
                    $jobOrderProduct->po_no = $request->po_no;
                }

                $jobOrderProduct->save();
                $jobOrderProductId = $jobOrderProduct->id;

                // Packing Images
                $packingImgs = [];

                // Old Packing
                $packingId = $request->packing_id[$i] ?? null;
                $overWritePacking = $request->overwrite_packing[$i] ?? null;
                $oldPictures = $request->old_packing_pictures[$i] ?? null;

                if (!empty($oldPictures)) {
                    $oldPicturesArr = explode(',', substr_replace($oldPictures, "", -1));
                    foreach ($oldPicturesArr as $oldPicture) {
                        $data = [
                            'picture_link' => $oldPicture,
                            'job_order_product_id' => $jobOrderProductId
                        ];
                        array_push($packingImgs, $data);
                    }
                }

                // Packing Images
                if (isset($request->file('packing_imgs')[$i]) && !empty($request->file('packing_imgs')[$i])) {
                    $files = $request->file('packing_imgs')[$i];

                    foreach ($files as $file) {
                        $packingImagePath = $file->store('job_order_packing_imgs');
                        $data = [
                            'picture_link' => $packingImagePath,
                            'job_order_product_id' => $jobOrderProductId
                        ];
                        array_push($packingImgs, $data);
                    }
                }
                if (!empty($packingImgs)) {
                    JobOrderProductPackingPicture::insert($packingImgs);
                }

                // Create new packing if non existed
                if ($overWritePacking == 1 && empty($packingId)) {
                    $productPacking = new ProductPacking;
                    $productPacking->product_id = $request->product_ids[$i];
                    $productPacking->packing_details = $request->product_packing[$i];
                    $productPacking->save();
                    $packingId = $productPacking->id;
                }

                // Overwrite Packing Pictures
                if ($overWritePacking == 1 && !empty($packingId)) {
                    ProductPackingPicture::where('product_packing_id', $packingId)->delete();
                    $overWritePictures = [];
                    foreach ($packingImgs as $packingImg) {
                        $data = [
                            'picture_link' => $packingImg['picture_link'],
                            'product_packing_id' => $packingId
                        ];
                        array_push($overWritePictures, $data);
                    }
                    ProductPackingPicture::insert($overWritePictures);
                }
            }
        }

        //Notify Users
        $notification = new Notification;
        $notification->type = 1;
        $notification->title = 'New Job Order Added';

        $notification->description = 'New Job Order Created By: <a target="_blank" href="' . route('users.show', $request->user()->id) . '">' . $request->user()->name . "</a> Job Order ID:" . $jobOrderId . " Manual NO: " . $request->order_no_manual;

        $notification->action_link = route('job-orders.show', $jobOrderId);
        $notification->save();

        Helper::logSystemActivity('Job Orders', 'Added job order successfully');

        // Back to index with success
        return redirect()->route('job-orders.index')->with('custom_success', 'Job Order has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print($id, Request $request)
    {
        Helper::logSystemActivity('Job Orders', 'View job order details id: ' . $id);

        $jobOrder = JobOrder::with(['jobProducts.product', 'jobProducts.packingPictures', 'customer', 'createdBy', 'updatedBy'])->find($id);
        // If Ajax Request Return Job Order Details
        $productArray = [];
        $ids = [];
        $products = [];

        foreach ($jobOrder->jobProducts as $jobProduct) {
            $arr = array(
                'id' => $jobProduct->id,
                'product_id' => $jobProduct->product_id,
                'order_id' => $jobProduct->order_id,
                'order_no_manual' => $jobOrder->order_no_manual,
                'customer_name' => $jobOrder->customer->customer_name,
                'country_name' => $jobOrder->customer->country_name,
                'site_name' => $jobOrder->site->name,
                'site_code' => $jobOrder->site->code,
                'product_name' => $jobProduct->product->model_name,
                'item_description' => $jobProduct->product->item_description,
                'packingPictures' => $jobProduct->packingPictures,
                'pictureImage' => $jobProduct->product->image,
                'packagingDetail' => $jobProduct->product_packing,
                'colors' => [
                    $jobProduct->product->color_name
                ],
                "quantity" => [
                    $jobProduct->quantity
                ],
                "product_test" => [
                    $jobProduct->product_test
                ],
                "qc_date" => [
                    $jobProduct->qc_date
                ],
                "crd_date" => [
                    $jobProduct->crd_date
                ],
                "po_no" => [
                    $jobProduct->po_no
                ],
                "container_vol" => [
                    $jobProduct->container_vol
                ],
                "truck_in" => [
                    $jobProduct->truck_in
                ],
                "product_test_remarks" => [
                    $jobProduct->product_test_remarks
                ],
                "remarks" => [
                    $jobProduct->remarks
                ],
                "created_at" =>
                $jobProduct->created_at,
                "updated_at" =>
                $jobProduct->updated_at

            );
            if (in_array($jobProduct->product_id, $ids)) {
                foreach ($products as $key => $pro) {
                    if ($pro['product_id'] == $jobProduct->product_id) {
                        array_push($pro['colors'], $jobProduct->color_name);
                        array_push($pro['quantity'], $jobProduct->color_name);
                        array_push($pro['product_test'], $jobProduct->product_test);
                        array_push($pro['qc_date'], $jobProduct->qc_date);
                        array_push($pro['crd_date'], $jobProduct->crd_date);
                        array_push($pro['po_no'], $jobProduct->po_no);
                        array_push($pro['container_vol'], $jobProduct->po_no);
                        array_push($pro['truck_in'], $jobProduct->truck_in);
                        array_push($pro['product_test_remarks'], $jobProduct->product_test_remarks);
                        array_push($pro['remarks'], $jobProduct->remarks);
                    }
                    $products[$key] = $pro;
                }
            } else {
                array_push($products, $arr);
                array_push($ids, $jobProduct->product_id);
            }
        }
        
        // dd($products);
        $pdf = PDF::loadView('job-orders.print', ['jobOrder' => $products]);
        return $pdf->download('pdf_file.pdf');
    }
    public function show($id, Request $request)
    {
        Helper::logSystemActivity('Job Orders', 'View job order details id: ' . $id);

        $jobOrder = JobOrder::with(['jobProducts.product', 'jobProducts.packingPictures', 'customer', 'createdBy', 'updatedBy'])->find($id);

        // If Ajax Request Return Job Order Details
        if($request->ajax()) {
            return $jobOrder;
        }

        return view('job-orders.show', ['jobOrder' => $jobOrder]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Job Orders', 'Edit job order form opened id: ' . $id);

        $multiSites = MultiSite::all();
        $jobOrder = JobOrder::with([
            'jobProducts.product.packing',
            'jobProducts.packingPictures',
            'customer',
            'site',
            'createdBy',
            'updatedBy'
            ])->find($id);
        return view('job-orders.edit', ['jobOrder' => $jobOrder, 'multiSites' => $multiSites]);
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
        // Validations
        $validatedData = $request->validate([
            'order_no_manual' => 'nullable|string|max:255',
            'po_no' => 'nullable|string|max:255',
            'qc_date' => 'nullable|date',
            'crd_date' => 'nullable|date',
            'truck_in' => 'nullable|date',
            'container_vol' => 'nullable|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'site_id' => 'required|exists:sites,id',
            'product_ids.*' => 'required|exists:products,id'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $jobOrder = JobOrder::findOrFail($id);

        // Update the item
        $jobOrder->order_no_manual = $request->order_no_manual;
        if (!empty($request->same_qc_date)) {
            $jobOrder->qc_date = $request->qc_date;
        } else {
            $jobOrder->qc_date = null;
        }
        if (!empty($request->same_crd_date)) {
            $jobOrder->crd_date = $request->crd_date;
            $jobOrder->truck_in = $request->truck_in;
        } else {
            $jobOrder->crd_date = null;
            $jobOrder->truck_in = null;
        }
        if (!empty($request->same_container_vol)) {
            $jobOrder->container_vol = $request->container_vol;
        } else {
            $jobOrder->container_vol = null;
        }
        if (!empty($request->same_po_no)) {
            $jobOrder->po_no = $request->po_no;
        } else {
            $jobOrder->po_no = null;
        }
        $jobOrder->customer_id = $request->customer_id;
        $jobOrder->site_id = $request->site_id;
        $jobOrder->updated_by = $request->user()->id;
        $jobOrder->save();

        // Add Products
        if (!empty($request->product_ids)) {
            $totalProducts = count($request->product_ids);

            for ($i = 0; $i < $totalProducts; $i++) {
                if (empty($request->product_ids[$i])) {
                    continue;
                }

                if (isset($request->job_order_product_ids[$i]) && $request->job_order_product_ids[$i] > 0) {
                    // Get the item for update
                    $jobOrderProduct = JobOrderProduct::findOrFail($request->job_order_product_ids[$i]);
                } else {
                    // create new item
                    $jobOrderProduct = new JobOrderProduct;
                }

                $jobOrderProduct->order_id = $id;
                $jobOrderProduct->product_id = $request->product_ids[$i];
                $jobOrderProduct->quantity = $request->quantity[$i];
                $jobOrderProduct->product_test = $request->product_test[$i] ?? 0;
                $jobOrderProduct->product_test_remarks = $request->product_test_remarks[$i];
                $jobOrderProduct->remarks = $request->product_remarks[$i];
                $jobOrderProduct->product_packing = $request->product_packing[$i];

                if (empty($request->same_qc_date)) {
                    $jobOrderProduct->qc_date = $request->product_qc_date[$i];
                } else {
                    $jobOrderProduct->qc_date = $request->qc_date;
                }
                if (empty($request->same_crd_date)) {
                    $jobOrderProduct->crd_date = $request->product_crd_date[$i];
                    $jobOrderProduct->truck_in = $request->product_truck_in[$i];
                } else {
                    $jobOrderProduct->crd_date = $request->crd_date;
                    $jobOrderProduct->truck_in = $request->truck_in;
                }
                if (empty($request->same_container_vol)) {
                    $jobOrderProduct->container_vol = $request->product_container_vol[$i];
                } else {
                    $jobOrderProduct->container_vol = $request->container_vol;
                }
                if (empty($request->same_po_no)) {
                    $jobOrderProduct->po_no = $request->product_po_no[$i];
                } else {
                    $jobOrderProduct->po_no = $request->po_no;
                }

                $jobOrderProduct->save();
                $jobOrderProductId = $jobOrderProduct->id;


                // Packing Images
                $packingImgs = [];

                // Old Packing
                $packingId = $request->packing_id[$i] ?? null;
                $overWritePacking = $request->overwrite_packing[$i] ?? null;
                $oldPictures = $request->old_packing_pictures[$i] ?? null;

                if (!empty($oldPictures)) {
                    $oldPicturesArr = explode(',', substr_replace($oldPictures, "", -1));
                    foreach ($oldPicturesArr as $oldPicture) {
                        $data = [
                            'picture_link' => $oldPicture,
                            'job_order_product_id' => $jobOrderProductId
                        ];
                        array_push($packingImgs, $data);
                    }
                }

                // Packing Images
                if (isset($request->file('packing_imgs')[$i]) && !empty($request->file('packing_imgs')[$i])) {
                    $files = $request->file('packing_imgs')[$i];

                    foreach ($files as $file) {
                        $packingImagePath = $file->store('job_order_packing_imgs');
                        $data = [
                            'picture_link' => $packingImagePath,
                            'job_order_product_id' => $jobOrderProductId
                        ];
                        array_push($packingImgs, $data);
                    }
                }
                if (!empty($packingImgs)) {
                    JobOrderProductPackingPicture::insert($packingImgs);
                }

                // Create new packing if non existed
                if ($overWritePacking == 1 && empty($packingId)) {
                    $productPacking = new ProductPacking;
                    $productPacking->product_id = $request->product_ids[$i];
                    $productPacking->packing_details = $request->product_packing[$i];
                    $productPacking->save();
                    $packingId = $productPacking->id;
                }

                // Overwrite Packing Pictures
                if ($overWritePacking == 1 && !empty($packingId)) {
                    ProductPackingPicture::where('product_packing_id', $packingId)->delete();
                    $overWritePictures = [];
                    foreach ($packingImgs as $packingImg) {
                        $data = [
                            'picture_link' => $packingImg['picture_link'],
                            'product_packing_id' => $packingId
                        ];
                        array_push($overWritePictures, $data);
                    }
                    ProductPackingPicture::insert($overWritePictures);
                }
            }
        }

        Helper::logSystemActivity('Job Orders', 'Edit job order successfull id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'JobOrder has been updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the job order by $id
        $jobOrder = JobOrder::findOrFail($id);

        // delete JobOrder
        $status = $jobOrder->delete();

        if ($status) {
            Helper::logSystemActivity('Job Orders', 'Delete job order successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'JobOrder has been deleted');
        } else {
            Helper::logSystemActivity('Job Orders', 'Delete job order failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'JobOrder was not deleted. Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyJobOrderProduct($id)
    {
        // Find the job order product by $id
        $jobOrderProduct = JobOrderProduct::findOrFail($id);

        // delete JobOrder Product
        $status = $jobOrderProduct->delete();

        if ($status) {
            Helper::logSystemActivity('Job Orders', 'Delete job order Product successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'Job Order Product has been deleted');
        } else {
            Helper::logSystemActivity('Job Orders', 'Delete Job Order Product failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Job Order Product was not deleted. Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteProductPicture($id)
    {
        // Find the picture by $id
        $picture = JobOrderProductPackingPicture::findOrFail($id);

        // delete Product
        Storage::delete($picture->picture_link);
        $status = $picture->delete();

        if ($status) {
            Helper::logSystemActivity('Job Orders', 'Delete Job Order product picture successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'Job Order Product picture has been deleted');
        } else {
            Helper::logSystemActivity('Job Orders', 'Delete Job Order product picture failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Delete Job Order Product picture was not deleted. Something went wrong.');
        }
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ordersListReport(Request $request)
    {
        Helper::logSystemActivity('Job Orders', 'Job Orders list Report View');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];

        // Prepare the Report Data
        $orders = JobOrder::with(['jobProducts.product', 'customer', 'createdBy', 'updatedBy'])
        ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
        ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
        ->orderBy('id', 'DESC')
        ->get();

        return view('job-orders.orders-list-report', [
            'orders' => $orders,
            'filters' => $filters
        ]);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ordersShippingReport(Request $request)
    {
        Helper::logSystemActivity('Job Orders', 'Job Orders Shipping Report View');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['order_id'] = $request->order_id ?? null;
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];

        // Prepare the Report Data
        if(empty($request->generate_report)) {
            $reports = [];
        } else {
            $startDate = $filters['dateRanges']['start'];
            $endDate = $filters['dateRanges']['end'];
            $orderId = $filters['order_id'];
            $reports = JobOrder::with(['jobProducts.product', 'bomItems.item', 'shipping'])
                            // ->when(!empty($startDate), function ($query) use ($startDate) {
                            //     return $query->where('created_at', '>=', $startDate);
                            // })
                            // ->when(!empty($endDate), function ($query) use ($endDate) {
                            //     return $query->where('created_at', '<=', $endDate);
                            // })
                            ->when(!empty($orderId), function ($query) use ($orderId) {
                                return $query->where('id', $orderId);
                            })
                            ->whereHas('jobProducts', function ($query) use ($startDate) {
                                return $query->when(!empty($startDate), function ($query) use ($startDate) {
                                    return $query->where('crd_date', '>=', $startDate);
                                });
                            })
                            ->whereHas('jobProducts', function ($query) use ($endDate) {
                                return $query->when(!empty($endDate), function ($query) use ($endDate) {
                                    return $query->where('crd_date', '<=', $endDate);
                                });
                            })
                            ->has('bomItems')
                            ->orderBy('id', 'DESC')
                            ->get();
        }

        return view('job-orders.orders-shipping-report', [
            'reports' => $reports,
            'filters' => $filters
        ]);
    }

    /**
     * Ajax Search the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxSearch(Request $request)
    {
        $search = $request->q;

        if (empty($search)) {
            return;
        }

        // Find the Job Orders
        $jobOrders = JobOrder::where('id', 'like', '%' . $search . '%')
            ->orWhere('order_no_manual', 'like', '%' . $search . '%')
            ->limit(10)->get();

        $response = [];
        foreach ($jobOrders as $jobOrder) {
            $response[] = array(
                "id" => $jobOrder->id,
                "text" => "#" . $jobOrder->id . " [" . $jobOrder->order_no_manual . "] "
            );
        }

        return $response;
    }
    public function addProducts(Request $request)
    {
        foreach (explode(',',$request->list) as $item) {
            $order = JobOrderReceivingList::where([
                "order_id"=>$request->orderId,
                "product_id"=>$request->pid
            ])->orderBy('id', 'DESC')->limit(1)->first();
            $neworder = $order->replicate();
            $neworder->product_id = $item;
            $neworder->date_in = date('Y-m-d');
            $neworder->do_no = 0;
            $neworder->received_quantity = 0;
            $neworder->extra_less = 0;
//            $neworder->balance = 0;
            $neworder->is_updated = 0;
            $neworder->save();
        }

        return json_encode(array("operation"=>true));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id, Request $request)
    {
        Helper::logSystemActivity('Job Orders', 'Duplicate job order id: ' . $id);

        $jobOrder = JobOrder::with(['jobProducts'])->findOrFail($id);

        $duplicateOrder = new JobOrder;

        $duplicateOrder->order_no_manual = $jobOrder->order_no_manual . " Copy";
        $duplicateOrder->qc_date = $jobOrder->qc_date;
        $duplicateOrder->crd_date = $jobOrder->crd_date;
        $duplicateOrder->container_vol = $jobOrder->container_vol;
        $duplicateOrder->po_no = $jobOrder->po_no;

        $oldOrderProducts = $jobOrder->jobProducts;
        $duplicateOrder->customer_id = $jobOrder->customer_id;
        $duplicateOrder->site_id = $jobOrder->site_id;
        $duplicateOrder->created_by = $request->user()->id;
        $duplicateOrder->updated_by = $request->user()->id;
        $duplicateOrder->save();
        $newOrderId = $duplicateOrder->id;

        foreach($oldOrderProducts as $product) {
            $duplicateJobOrderProduct = new JobOrderProduct;

            $duplicateJobOrderProduct->order_id = $newOrderId;
            $duplicateJobOrderProduct->product_id = $product->product_id;
            $duplicateJobOrderProduct->quantity = $product->quantity;
            $duplicateJobOrderProduct->product_test = $product->product_test;
            $duplicateJobOrderProduct->product_test_remarks = $product->product_test_remarks;
            $duplicateJobOrderProduct->remarks = $product->product_remarks;
            $duplicateJobOrderProduct->product_packing = $product->product_packing;
            $duplicateJobOrderProduct->qc_date = $product->qc_date;
            $duplicateJobOrderProduct->crd_date = $product->crd_date;
            $duplicateJobOrderProduct->container_vol = $product->container_vol;
            $duplicateJobOrderProduct->po_no = $product->po_no;

            $duplicateJobOrderProduct->save();
            $duplicateJobOrderProductId = $duplicateJobOrderProduct->id;

            // Packing Images
            $packingImgs = [];
            $oldPictures = JobOrderProductPackingPicture::where('job_order_product_id', $product->id)->get();
            foreach ($oldPictures as $file) {
                $packingImagePath = $file->picture_link;
                $data = [
                    'picture_link' => $packingImagePath,
                    'job_order_product_id' => $duplicateJobOrderProductId
                ];
                array_push($packingImgs, $data);
            }
            if (!empty($packingImgs)) {
                JobOrderProductPackingPicture::insert($packingImgs);
            }
        }

        return back()->with('custom_success', 'Job Order duplicated');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function productPackingByOrder(Request $request)
    {
        $orderId = $request->order_id ?? 0;
        $productId = $request->product_id ?? 0;

        $jobOrder = JobOrderProduct::with(['product', 'packingPictures'])
        ->where('order_id', $orderId)
        ->where('product_id', $productId)
        ->first();

        return $jobOrder;
    }
}
