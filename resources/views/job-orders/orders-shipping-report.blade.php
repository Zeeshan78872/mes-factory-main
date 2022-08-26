@extends('layouts.app')

@section('custom_css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/datatables.min.css">
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/css/buttons.bootstrap5.min.css">
<link rel="stylesheet"
    href="{{ url('/assets') }}/plugins/DataTables/DataTables-1.10.25/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet"
    href="{{ url('/assets') }}/plugins/DataTables/Responsive-2.2.9/css/responsive.bootstrap5.min.css">

<link rel="stylesheet" type="text/css" href="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.css" />
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
                <h1 class="m-0 text-dark"><i class="fas fa-clipboard-list"></i> Schedule Report</h1>
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
                            Job Orders Shipping Schedule Report
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <h4>Filters:</h4>
                        <form class="row g-3 mb-4" method="GET" action="">
                            <div class="col-md-3">
                                <label for="dates-range" class="form-label">Date Range</label>
                                <input type="text" class="form-control form-control-sm" id="dates-range" value="" />
                                <input type="hidden" name="dateStart" id="dateStart" value="{{ $filters['dateRanges']['start'] ?? '' }}">
                                <input type="hidden" name="dateEnd" id="dateEnd" value="{{ $filters['dateRanges']['end'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label for="order_id" class="form-label">Job Order</label>
                                <select class="select2AutoFocus ajax-job-orders form-select" name="order_id" id="order_id">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-12">
                                <input type="hidden" name="generate_report" value="1">
                                <button type="submit" class="btn btn-primary">Apply Filters</button>
                            </div>
                        </form>

                        <hr>

                        <div class="row">
                            @foreach ($reports as $item)
                            <div class="col-md-6">
                                <p>
                                    <b>Job Order:</b> {{ $item->order_no_manual }}
                                </p>
                                <p>
                                    <b>QC Date:</b> {{ $item->qc_date }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <b>Truck In:</b> {{ $item->crd_date }}
                                </p>
                                <p>
                                    <b>Truck Out:</b> {{ $item->shipping->truck_out_date ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered" id="dataTable" style="width:100%">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>S. NO.</th>
                                                <th>Item</th>
                                                <th>Color</th>
                                                <th>Qty</th>
                                                <th>Carton</th>
                                                <th>CBM</th>
                                                <th>Total CBM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalQty = 0;
                                                $totalCarton = 0;
                                                $totalCBM = 0;
                                                $totalFinalCBM = 0;
                                            @endphp
                                            @foreach ($item->jobProducts as $jobProduct)
                                                @php
                                                    $cartonItem = null;
                                                    
                                                    foreach($item->bomItems as $bomitem) {
                                                        if(strtolower($bomitem->item->product_name) == strtolower('EXTERNAL CARTON')) {
                                                            $cartonItem = $bomitem;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $jobProduct->product->model_name }} [{{ $jobProduct->product->product_name }}]</td>
                                                <td>{{ $jobProduct->product->color_name }}</td>
                                                <td>{{ $jobProduct->quantity }}</td>
                                                <td>
                                                    @if($cartonItem !== null)
                                                    {{ $cartonItem->quantity * $jobProduct->quantity }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($cartonItem !== null)
                                                    {{ number_format(($cartonItem->length * $cartonItem->width * $cartonItem->thick)/1000000000, 4) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($cartonItem !== null)
                                                    {{ number_format(((($cartonItem->length * $cartonItem->width * $cartonItem->thick)/1000000000) * ($cartonItem->quantity * $jobProduct->quantity)), 4) }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($cartonItem !== null)
                                                @php
                                                    $totalQty += $jobProduct->quantity;
                                                    $totalCarton += $cartonItem->quantity * $jobProduct->quantity;
                                                    $totalCBM += ($cartonItem->length * $cartonItem->width * $cartonItem->thick)/1000000000;
                                                    $totalFinalCBM += ((($cartonItem->length * $cartonItem->width * $cartonItem->thick)/1000000000) *
                                                    ($cartonItem->quantity * $jobProduct->quantity));
                                                @endphp
                                            @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold">
                                                <td>Totals</td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    {{ number_format($totalQty, 0) }}
                                                </td>
                                                <td>
                                                    {{ number_format($totalCarton) }}
                                                </td>
                                                <td>
                                                    {{ number_format($totalCBM, 4) }}
                                                </td>
                                                <td>
                                                    {{ number_format($totalFinalCBM, 4) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <hr class="mt-4 mb-4">
                            @endforeach
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

<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.js"></script>

<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>

<script>
$(document).ready(function(){
    // Date Range Picker
    var start = {!! 'moment("'.$filters['dateRanges']['start'].'")' ?? "moment().startOf('month')" !!};
    var end = {!! 'moment("'.$filters['dateRanges']['end'].'")' ?? "moment()" !!};
    $('#dates-range').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        },
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end, label) {
        $('#dateStart').val(start.format('YYYY-MM-DD'));
        $('#dateEnd').val(end.format('YYYY-MM-DD'));
    });

    $('#dates-range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));

        $('#dateStart').val(picker.startDate.format('YYYY-MM-DD'));
        $('#dateEnd').val(picker.endDate.format('YYYY-MM-DD'));
    });

    $('#dates-range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

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