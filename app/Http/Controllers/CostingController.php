<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\SystemSetting;
use App\Models\JobOrder;
use App\Models\DailyProduction;
use App\Models\JobOrderReceivingList;
use App\Models\ShippingItem;
use App\Models\Product;
use Helper;
use DB;

class CostingController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Department Permissions
        $this->middleware('RolePermissionCheck:costing.daily-production-report')->only(['dailyProductionReport']);
    }

    /**
     * Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dashboardReport(Request $request)
    {
        Helper::logSystemActivity('Costing', 'Open Dashboard report generate page');

        // Filters
        $filters['jobOrderId'] =  $request->order_id ?? null;

        $generate_report = null;
        $jobOrder = null;
        $departmentWiseProductionBasicTimeResults = null;
        $departmentWiseProductionOverTimeResults = null;
        $purchaseCostSummary = null;
        $chemicalReportResults = null;

        $departments = Department::get();
        $settings = SystemSetting::first();
        if($request->generate_report) {
            $generate_report = 1;
            $order_id = $filters['jobOrderId'];

            // Get Job Order
            $jobOrder = JobOrder::with('jobProducts.product')->find($filters['jobOrderId']);

            // Get Production Cost
            $basicTimeStart = $settings->basic_time_start;
            $basicTimeEnd = $settings->basic_time_end;
            $overTimeStart = $settings->over_time_start;
            $overTimeEnd = $settings->over_time_end;

            $departmentWiseProductionBasicTimeResults = DB::table('daily_productions AS DP')
                                        ->select(DB::raw('(SUM(DPP.difference_seconds)/60/60) AS basic_total_time, D.name AS department_name, DP.department_id '))
                                        ->join('daily_production_progresses AS DPP', 'DPP.daily_production_id', '=', 'DP.id')
                                        ->join('departments AS D', 'D.id', '=', 'DP.department_id')
                                        ->join('daily_production_stock_cards AS DPSC', 'DPSC.daily_production_id', '=', 'DP.id')
                                        ->join('product_stock_cards AS PSC', 'PSC.id', '=', 'DPSC.stock_card_id')
                                        ->where('DP.is_ended', '=', 1)
                                        ->where('DPP.timer_type', '=', 1)
                                        ->whereRaw("TIME(DPP.started_at) >= '$basicTimeStart'")
                                        ->whereRaw("TIME(DPP.started_at) <= '$basicTimeEnd'")
                                        ->where('PSC.order_id', $order_id)
                                        ->groupBy('DP.department_id')
                                        ->get();

            $departmentWiseProductionOverTimeResults = DB::table('daily_productions AS DP')
                                            ->select(DB::raw('(SUM(DPP.difference_seconds)/60/60) AS over_total_time, D.name AS department_name, DP.department_id '))
                                            ->join('daily_production_progresses AS DPP', 'DPP.daily_production_id', '=', 'DP.id')
                                            ->join('departments AS D', 'D.id', '=', 'DP.department_id')
                                            ->join('daily_production_stock_cards AS DPSC', 'DPSC.daily_production_id', '=', 'DP.id')
                                            ->join('product_stock_cards AS PSC', 'PSC.id', '=', 'DPSC.stock_card_id')
                                            ->where('DP.is_ended', '=', 1)
                                            ->where('DPP.timer_type', '=', 1)
                                            ->whereRaw("TIME(DPP.started_at) >= '$overTimeStart'")
                                            ->orWhereRaw("TIME(DPP.started_at) <= '$overTimeEnd'")
                                            ->where('PSC.order_id', $order_id)
                                            ->groupBy('DP.department_id')
                                            ->get();

            // Get Purchase Cost
            $purchaseCostSummary = DB::table('job_order_receiving_lists AS JORL')
                                ->select(DB::raw('SUM(JORL.received_quantity) as total_qty, JOPL.item_price_per_unit, P.material'))
                                ->join('job_order_purchase_lists AS JOPL', 'JOPL.id', '=', 'JORL.purchase_id')
                                ->join('products AS P', 'P.id', '=', 'JOPL.item_id')
                                ->join('product_categories AS PC', 'PC.id', '=', 'P.subcategory_id')
                                ->where('JORL.order_id', $order_id)
                                ->groupBy('P.material')
                                ->get();

            // Chemical Cost
            $chemicalReportResults = DB::table('daily_production_chemicals AS DPC')
                            ->select(DB::raw('
                                JO.order_no_manual,
                                DPC.*,
                                DATE(DP.created_at) as created_at,
                                P2.model_name as P2_model_name,
                                P2.product_name as P2_product_name,
                                P2.color_name as P2_color_name,
                                P.model_name,
                                P.price_per_unit,
                                P.product_name'
                            ))
                            ->join('daily_productions AS DP', 'DP.id', '=', 'DPC.daily_production_id')
                            ->join('product_stock_cards AS PSC', 'PSC.id', '=', 'DPC.stock_card_id')
                            ->join('daily_production_stock_cards AS DPSC', 'DPSC.daily_production_id', '=', 'DP.id')
                            ->join('product_stock_cards AS PSC2', 'PSC2.id', '=', 'DPSC.stock_card_id')
                            ->join('products AS P2', 'P2.id', '=', 'PSC2.product_id')
                            ->join('job_orders AS JO', 'JO.id', '=', 'PSC2.order_id')
                            ->join('products AS P', 'P.id', '=', 'PSC.product_id')
                            ->where('DP.is_ended', '=', 1)
                            ->where('PSC2.order_id', $order_id)
                            ->get();

        }

        return view('costings.dashboard-report', [
            'departmentWiseProductionBasicTimeResults' => $departmentWiseProductionBasicTimeResults,
            'departmentWiseProductionOverTimeResults' => $departmentWiseProductionOverTimeResults,
            'purchaseCostSummary' => $purchaseCostSummary,
            'chemicalReportResults' => $chemicalReportResults,
            'jobOrder' => $jobOrder,
            'departments' => $departments,
            'settings' => $settings,
            'filters' => $filters,
            'generate_report' => $generate_report
        ]);
    }

    /**
     * Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function jobOrderSummaryReport(Request $request)
    {
        Helper::logSystemActivity('Costing', 'Open Job Summary report generate page');

        // Filters
        $filters['jobOrderId'] =  $request->order_id ?? null;

        return view('costings.job-summary-report', [
            'reportItems' => [],
            'filters' => $filters
        ]);
    }

    /**
     * Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dailyProductionReport(Request $request)
    {
        Helper::logSystemActivity('Costing', 'Open Daily production report generate page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];
        $filters['department_id'] = $request->department_id ?? 0;
        $filters['order_id'] = $request->order_id ?? null;
        $filters['model_id'] = $request->model_id ?? null;
        $order_id = $filters['order_id'];
        $model_id = $filters['model_id'];
        $department_id = $filters['department_id'];

        $reportTitle = '';

        if(!empty($order_id)) {
            $jobOrderData = JobOrder::find($order_id);
            $reportTitle .= $jobOrderData->order_no_manual . " ";
        }
        if (!empty($model_id) && $model_id != "Select Job Order First") {
            $productModelData = Product::find($model_id);
            $reportTitle .= "- [" . $productModelData->model_name . "] " . $productModelData->product_name;
        }

        $departments = Department::get();
        $settings = SystemSetting::first();
        $basicTimeStart = $settings->basic_time_start;
        $basicTimeEnd = $settings->basic_time_end;
        $overTimeStart = $settings->over_time_start;
        $overTimeEnd = $settings->over_time_end;

        $departmentWiseBasicTimeResults = DB::table('daily_productions AS DP')
                                    ->select(DB::raw('(SUM(DPP.difference_seconds)/60/60) AS basic_total_time, D.name AS department_name, DP.department_id '))
                                    ->join('daily_production_progresses AS DPP', 'DPP.daily_production_id', '=', 'DP.id')
                                    ->join('departments AS D', 'D.id', '=', 'DP.department_id')
                                    ->join('daily_production_stock_cards AS DPSC', 'DPSC.daily_production_id', '=', 'DP.id')
                                    ->join('product_stock_cards AS PSC', 'PSC.id', '=', 'DPSC.stock_card_id')
                                    ->where('DP.is_ended', '=', 1)
                                    ->where('DPP.timer_type', '=', 1)
                                    ->whereDate('DP.created_at', '>=', $filters['dateRanges']['start'])
                                    ->whereDate('DP.created_at', '<=', $filters['dateRanges']['end'])
                                    ->whereRaw("TIME(DPP.started_at) >= '$basicTimeStart'")
                                    ->whereRaw("TIME(DPP.started_at) <= '$basicTimeEnd'")
                                    ->when(!empty($department_id), function ($query) use ($department_id) {
                                        return $query->where('DP.department_id', $department_id);
                                    })
                                    ->when(!empty($order_id), function ($query) use ($order_id) {
                                        return $query->where('PSC.order_id', $order_id);
                                    })
                                    ->when(!empty($model_id), function ($query) use ($model_id) {
                                        return $query->where('PSC.product_id', $model_id);
                                    })
                                    ->groupBy('DP.department_id')
                                    ->get();

        $departmentWiseOverTimeResults = DB::table('daily_productions AS DP')
                                        ->select(DB::raw('(SUM(DPP.difference_seconds)/60/60) AS over_total_time, D.name AS department_name, DP.department_id '))
                                        ->join('daily_production_progresses AS DPP', 'DPP.daily_production_id', '=', 'DP.id')
                                        ->join('departments AS D', 'D.id', '=', 'DP.department_id')
                                        ->join('daily_production_stock_cards AS DPSC', 'DPSC.daily_production_id', '=', 'DP.id')
                                        ->join('product_stock_cards AS PSC', 'PSC.id', '=', 'DPSC.stock_card_id')
                                        ->where('DP.is_ended', '=', 1)
                                        ->where('DPP.timer_type', '=', 1)
                                        ->whereDate('DP.created_at', '>=', $filters['dateRanges']['start'])
                                        ->whereDate('DP.created_at', '<=', $filters['dateRanges']['end'])
                                        ->whereRaw("TIME(DPP.started_at) >= '$overTimeStart'")
                                        ->orWhereRaw("TIME(DPP.started_at) <= '$overTimeEnd'")
                                        ->when(!empty($department_id), function ($query) use ($department_id) {
                                            return $query->where('DP.department_id', $department_id);
                                        })
                                        ->when(!empty($order_id), function ($query) use ($order_id) {
                                            return $query->where('PSC.order_id', $order_id);
                                        })
                                        ->when(!empty($model_id), function ($query) use ($model_id) {
                                            return $query->where('PSC.product_id', $model_id);
                                        })
                                        ->groupBy('DP.department_id')
                                        ->get();

        $reportItems = DailyProduction::with(['stockCards.stockCard.product', 'department', 'workers.worker', 'machines', 'progresses'])
                                    ->where('is_ended', '=', 1)
                                    ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                                    ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                                    ->when(!empty($department_id), function ($query) use ($department_id) {
                                        return $query->where('department_id', $department_id);
                                    })
                                    ->whereHas('stockCards.stockCard', function ($query) use ($order_id) {
                                        return $query->when(!empty($order_id), function ($query) use ($order_id) {
                                            return $query->where('order_id', $order_id);
                                        });
                                    })
                                    ->orderBy('id', 'DESC')
                                    ->get();

        return view('costings.daily-production-report', [
            'reportItems' => $reportItems,
            'filters' => $filters,
            'departments' => $departments,
            'departmentWiseBasicTimeResults' => $departmentWiseBasicTimeResults,
            'departmentWiseOverTimeResults' => $departmentWiseOverTimeResults,
            'reportTitle' => $reportTitle,
            'basicTimeStart' => $basicTimeStart,
            'basicTimeEnd' => $basicTimeEnd,
            'overTimeStart' => $overTimeStart,
            'overTimeEnd' => $overTimeEnd
        ]);
    }

    /**
     * Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function chemicalUsageReport(Request $request)
    {
        Helper::logSystemActivity('Costing', 'Open Chemical Usage report generate page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];
        $filters['order_id'] = $request->order_id ?? null;
        $filters['model_id'] = $request->model_id ?? null;

        $order_id = $filters['order_id'];
        $model_id = $filters['model_id'];

        $reportTitle = '';

        if (!empty($order_id)) {
            $jobOrderData = JobOrder::find($order_id);
            $reportTitle .= $jobOrderData->order_no_manual . " ";
        }
        if (!empty($model_id) && $model_id != "Select Job Order First") {
            $productModelData = Product::find($model_id);
            $reportTitle .= "- [" . $productModelData->model_name . "] " . $productModelData->product_name;
        }

        $reportResults = DB::table('daily_production_chemicals AS DPC')
                            ->select(DB::raw('
                            JO.order_no_manual,
                            DPC.*,
                            DATE(DP.created_at) as created_at,
                            P3.model_name as P3_model_name,
                            P2.model_name as P2_model_name,
                            P2.product_name as P2_product_name,
                            P2.color_name as P2_color_name,
                            P.model_name,
                            P.price_per_unit,
                            P.product_name'
                            ))
                            ->join('daily_productions AS DP', 'DP.id', '=', 'DPC.daily_production_id')
                            ->join('product_stock_cards AS PSC', 'PSC.id', '=', 'DPC.stock_card_id')

                            ->join('daily_production_stock_cards AS DPSC', 'DPSC.daily_production_id', '=', 'DP.id')
                            ->join('product_stock_cards AS PSC2', 'PSC2.id', '=', 'DPSC.stock_card_id')
                            ->join('products AS P2', 'P2.id', '=', 'PSC2.product_id')


                            ->join('product_stock_cards AS PSC3', 'PSC3.id', '=', 'DPSC.stock_card_id')
                            ->join('products AS P3', 'P3.id', '=', 'PSC3.job_product_id')

                            ->join('job_orders AS JO', 'JO.id', '=', 'PSC2.order_id')

                            ->join('products AS P', 'P.id', '=', 'PSC.product_id')
                            ->where('DP.is_ended', '=', 1)
                            ->when(!empty($order_id), function ($query) use ($order_id) {
                                return $query->where('PSC2.order_id', $order_id);
                            })
                            ->when(!empty($model_id), function ($query) use ($model_id) {
                                return $query->where('P3.id', $model_id);
                            })
                            ->whereDate('DP.created_at', '>=', $filters['dateRanges']['start'])
                            ->whereDate('DP.created_at', '<=', $filters['dateRanges']['end'])
                            ->get();
        return view('costings.chemical-usage-report', [
            'reportResults' => $reportResults,
            'filters' => $filters,
            'reportTitle' => $reportTitle
        ]);
    }

    /**
     * Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function timeSummaryReport(Request $request)
    {
        Helper::logSystemActivity('Costing', 'Open Time Summary report generate page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];
        $filters['order_id'] = $request->order_id ?? null;
        $filters['model_id'] = $request->model_id ?? null;

        $order_id = $filters['order_id'];
        $model_id = $filters['model_id'];

        $reportTitle = '';

        if (!empty($order_id)) {
            $jobOrderData = JobOrder::find($order_id);
            $reportTitle .= $jobOrderData->order_no_manual . " ";
        }
        if (!empty($model_id) && $model_id != "Select Job Order First") {
            $productModelData = Product::find($model_id);
            $reportTitle .= "- [" . $productModelData->model_name . "] " . $productModelData->product_name;
        }

        $reportItems = DailyProduction::with(['stockCards.stockCard.product', 'workers.worker', 'progresses'])
                                    ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                                    ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                                    ->whereHas('stockCards.stockCard', function ($query) use ($order_id) {
                                        return $query->when(!empty($order_id), function ($query) use ($order_id) {
                                            return $query->where('order_id', $order_id);
                                        });
                                    })
                                    ->groupBy('department_id')
                                    ->orderBy('id', 'DESC')
                                    ->get();
        // echo json_encode($reportItems);
        // die();

        $departments = Department::get();

        return view('costings.time-summary-report', [
            'reportItems' => $reportItems,
            'departments' => $departments,
            'filters' => $filters,
            'reportTitle' => $reportTitle
        ]);
    }

    /**
     * Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function purchaseCostReport(Request $request)
    {
        Helper::logSystemActivity('Costing', 'Open Purchase Cost report generate page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];
        $filters['order_id'] = $request->order_id ?? null;
        $filters['model_id'] = $request->model_id ?? null;

        $order_id = $filters['order_id'];
        $model_id = $filters['model_id'];

        $reportTitle = '';

        if (!empty($order_id)) {
            $jobOrderData = JobOrder::find($order_id);
            $reportTitle .= $jobOrderData->order_no_manual . " ";
        }
        if (!empty($model_id) && $model_id != "Select Job Order First") {
            $productModelData = Product::find($model_id);
            $reportTitle .= "- [" . $productModelData->model_name . "] " . $productModelData->product_name;
        }

        $reportItems = JobOrderReceivingList::with(['purchase.item.subCategory'])
                    ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                    ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                    ->when(!empty($order_id), function ($query) use ($order_id) {
                        return $query->where('order_id', $order_id);
                    })
                    ->orderBy('id', 'DESC')
                    ->get();

        $reportItemsSummary = DB::table('job_order_receiving_lists AS JORL')
                                ->select(DB::raw('SUM(JORL.received_quantity) as total_qty, JOPL.item_price_per_unit, P.material'))
                                ->join('job_order_purchase_lists AS JOPL', 'JOPL.id', '=', 'JORL.purchase_id')
                                ->join('products AS P', 'P.id', '=', 'JOPL.item_id')
                                ->join('product_categories AS PC', 'PC.id', '=', 'P.subcategory_id')
                                ->whereDate('JORL.created_at', '>=', $filters['dateRanges']['start'])
                                ->whereDate('JORL.created_at', '<=', $filters['dateRanges']['end'])
                                ->groupBy('P.material')
                                ->get();

        return view('costings.purchase-cost-report', [
            'reportItemsSummary' => $reportItemsSummary,
            'reportItems' => $reportItems,
            'filters' => $filters,
            'reportTitle' => $reportTitle
        ]);
    }

    /**
     * Report
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loadingCostReport(Request $request)
    {
        Helper::logSystemActivity('Costing', 'Open Loading Cost report generate page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];
        $filters['order_id'] = $request->order_id ?? null;
        $filters['model_id'] = $request->model_id ?? null;

        $order_id = $filters['order_id'];
        $model_id = $filters['model_id'];

        $reportTitle = '';

        if (!empty($order_id)) {
            $jobOrderData = JobOrder::find($order_id);
            $reportTitle .= $jobOrderData->order_no_manual . " ";
        }
        if (!empty($model_id) && $model_id != "Select Job Order First") {
            $productModelData = Product::find($model_id);
            $reportTitle .= "- [" . $productModelData->model_name . "] " . $productModelData->product_name;
        }

        $reportItems = ShippingItem::with(['worker', 'product', 'shipping', 'progresses'])
                    ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                    ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                    ->whereHas('shipping', function ($query) use ($order_id) {
                        return $query->when(!empty($order_id), function ($query) use ($order_id) {
                            return $query->where('order_id', $order_id);
                        });
                    })
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('costings.loading-cost-report', [
            'reportItems' => $reportItems,
            'filters' => $filters,
            'reportTitle' => $reportTitle,
            'systemSetting'=> $settings
        ]);
    }
}
