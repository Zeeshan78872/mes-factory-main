@extends('layouts.app')

@section('custom_css')
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">

<style>
    .dt-button-collection .dropdown-menu {
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-calculator"></i> Dashboard Report</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">
                            Generate Dashboard Report
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <form class="row g-3 mb-4" method="POST" action="">
                            @csrf
                            <div class="col-md-3">
                                <label for="order_id" class="form-label">Job Order</label>
                                <select class="select2AutoFocus ajax-job-orders form-select" name="order_id" id="order_id">
                                    <option></option>
                                    @if($jobOrder)
                                    <option value="{{ $jobOrder->id }}" selected>{{ $jobOrder->order_no_manual }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 d-print-none">
                                <input type="hidden" name="generate_report" value="1">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </form>

                        <hr>

                        @if($generate_report)
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Job Order Details:</h2>
                                @php
                                    $totalQtyOrder = 0;  
                                @endphp
                                @foreach($jobOrder->jobProducts as $jobProduct)
                                    @php
                                        $totalQtyOrder += $jobProduct->quantity;
                                    @endphp
                                @endforeach
                                <p>
                                    <b>Job Order No:</b> {{$jobOrder->order_no_manual}}, <b>Total Qty: </b> {{ $totalQtyOrder }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h2 class="mt-2">Job Order Products:</h2>
                                @foreach($jobOrder->jobProducts as $jobProduct)
                                <p>
                                    <b>Model:</b> {{ $jobProduct->product->model_name }}, <b>Qty:</b> {{ $jobProduct->quantity }}
                                </p>
                                @endforeach
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <h2>Purchase Cost:</h2>
                                <table class="table table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="text-center bg-dark text-white">
                                            <th scope="col" colspan="2">Summary</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Category</th>
                                            <th scope="col">Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $totalCostSummary = 0;
                                        @endphp
                                        @foreach($purchaseCostSummary as $item)
                                        @php
                                        $totalCostSummary += $item->item_price_per_unit * $item->total_qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->material ?? "N/A" }}</td>
                                            <td>RM {{ number_format($item->item_price_per_unit * $item->total_qty, 4) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><b>Total</b></td>
                                            <td><b>RM {{ number_format($totalCostSummary, 4) }}</b></td>
                                        </tr>
                                    </tfoot>
                                </table><table class="table table-striped table-hover table-bordered align-middle">
                                    <thead>
                                        <tr class="text-center bg-dark text-white">
                                            <th scope="col" colspan="2">Summary</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Category</th>
                                            <th scope="col">Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalCostSummary = 0;    
                                        @endphp
                                        @foreach($purchaseCostSummary as $item)
                                        @php
                                            $totalCostSummary += $item->item_price_per_unit * $item->total_qty;    
                                        @endphp
                                        <tr>
                                            <td>{{ $item->material ?? "N/A" }}</td>
                                            <td>RM {{ number_format($item->item_price_per_unit * $item->total_qty, 4) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td><b>Total</b></td>
                                            <td><b>RM {{ number_format($totalCostSummary, 4) }}</b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h2>Purchase Cost Chart:</h2>
                                <div id="purchaseCostChart"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h2>Production Cost:</h2>
                                <hr>
                            </div>
                            @php
                                $productionCostArr = [];
                            @endphp
                            @foreach($departments as $department)
                                @php
                                    if(!empty($filters['department_id']) && $department->id != $filters['department_id']) {
                                        continue;
                                    }   
                                @endphp
                                <div class="col-md-4 col-print-4">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-bordered align-middle">
                                            <thead>
                                                <tr class="text-center bg-dark text-white">
                                                    <th scope="col" colspan="3">{{ $department->name }}</th>
                                                </tr>
                                                <tr>
                                                    <th scope="col"></th>
                                                    <th scope="col">TIME</th>
                                                    <th scope="col">COST</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope="col">BASIC</th>
                                                    <th scope="col">
                                                        @php
                                                            $totalBasicTime = 0;   
                                                        @endphp
                                                        @foreach($departmentWiseProductionBasicTimeResults as $result)
                                                            @if($result->department_id === $department->id)
                                                                {{ number_format($result->basic_total_time, 4) }}
                                                                @php
                                                                    $totalBasicTime += $result->basic_total_time;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    </th>
                                                    <th scope="col">
                                                        @php
                                                        $totalBasicTimeCost = 0;
                                                        @endphp
                                                        @foreach($departmentWiseProductionBasicTimeResults as $result)
                                                            @if($result->department_id === $department->id)
                                                                {{ number_format($result->basic_total_time, 4) }}
                                                                @php
                                                                    $totalBasicTimeCost += $result->basic_total_time;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col">OVER TIME</th>
                                                    <th scope="col">
                                                        @php
                                                        $totalOverTime = 0;
                                                        @endphp
                                                        @foreach($departmentWiseProductionOverTimeResults as $result)
                                                            @if($result->department_id === $department->id)
                                                                {{ number_format($result->over_total_time, 4) }}
                                                                @php
                                                                $totalOverTime += $result->over_total_time;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    </th>
                                                    <th scope="col">
                                                        @php
                                                        $totalOverTimeCost = 0;
                                                        @endphp
                                                        @foreach($departmentWiseProductionOverTimeResults as $result)
                                                            @if($result->department_id === $department->id)
                                                                {{ number_format($result->over_total_time, 4) }}
                                                                @php
                                                                $totalOverTimeCost += $result->over_total_time;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col">TOTAL</th>
                                                    <th scope="col">{{ number_format($totalBasicTime + $totalOverTime, 4) }}</th>
                                                    <th scope="col">{{ number_format($totalBasicTimeCost + $totalOverTimeCost, 4) }}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @php
                                    $tempArr = [
                                        'department'=> $department->name,
                                        'cost' => ($totalBasicTimeCost + $totalOverTimeCost)
                                    ];
                                    array_push($productionCostArr, $tempArr);
                                @endphp
                            @endforeach

                            <div class="col-md-12">
                                <hr>
                                <h2>Production Cost Chart:</h2>
                                <hr>
                                <div id="productionCostChart"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h2>Chemical Cost:</h2>
                                <hr>

                                <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>MODEL</th>
                                            <th>PART LIST</th>
                                            <th>COLOR</th>
                                            <th>CHEMICAL</th>
                                            <th>PRICE PER UNIT/LITRE</th>
                                            <th>USED UNIT/LITRE</th>
                                            <th>COST</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chemicalReportResults as $result)
                                        <tr>
                                            <td>{{ $result->P2_model_name }}</td>
                                            <td>{{ $result->P2_product_name }}</td>
                                            <td>{{ $result->P2_color_name }}</td>
                                            <td>{{ $result->product_name }}</td>
                                            <td>{{ $result->price_per_unit }}</td>
                                            <td>{{ $result->quantity }}</td>
                                            <td>{{ number_format($result->quantity * $result->price_per_unit, 4) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('custom_js')
{{-- Custom JS --}}
<script src="{{ url('/assets') }}/custom.js"></script>

<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>

@if($generate_report)
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    // draws it.
    function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Material');
        data.addColumn('number', 'Cost');
        data.addRows([
            @foreach($purchaseCostSummary as $item)
            ['{{ $item->material ?? "N/A" }}', {{ $item->item_price_per_unit * $item->total_qty }}],
            @endforeach
            ['a', 0]
        ]);
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('purchaseCostChart'));
        chart.draw(data, {'height': 400});
    }
    
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart2);
    // draws it.
    function drawChart2() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Department');
        data.addColumn('number', 'Cost');
        data.addRows([
            @foreach($productionCostArr as $item)
            ['{{ $item['department'] }}', {{ $item['cost'] }}],
            @endforeach
            ['a', 0]
        ]);
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('productionCostChart'));
        chart.draw(data, {'height': 500});
    }
</script>
@endif

<script>
    $(document).ready(function() {
        // Ajax Select2 Job Orders
        var ajaxJobOrdersOptions = {
            "language": {
                "noResults": function() {
                return "No Results Found... <a href='{{ route('job-orders.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-clipboard-list'></i> Add New Job Order</a>";
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            width: '100%',
            placeholder: 'Search Job Order',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('job-orders.search') }}',
                dataType: 'json',
                delay: 800,
                processResults: function (response) {
                return {
                    results: response
                };
                },
                cache: true
            }
        };
        $('.ajax-job-orders').select2(ajaxJobOrdersOptions);
    });
</script>
@endsection