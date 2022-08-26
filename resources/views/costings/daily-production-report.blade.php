@extends('layouts.app')

@section('custom_css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/datatables.min.css">
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/DataTables-1.10.25/css/dataTables.bootstrap5.min.css">
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
                <h1 class="m-0 text-dark"><i class="fas fa-calculator"></i> Costing Report</h1>
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
                            Generate Daily Production Report
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <form class="row g-3 mb-4" method="POST" action="">
                            @csrf
                            <div class="col-md-3">
                                <label for="dates-range" class="form-label">Date Range</label>
                                <input type="text" class="form-control form-control-sm" id="dates-range" value="" />
                                <input type="hidden" name="dateStart" id="dateStart"
                                    value="{{ $filters['dateRanges']['start'] ?? '' }}">
                                <input type="hidden" name="dateEnd" id="dateEnd"
                                    value="{{ $filters['dateRanges']['end'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="mb-2">Job Order:</label>
                                    <select class="select2AutoFocus ajax-job-orders form-select" name="order_id">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="mb-2">Product Model:</label>
                                    <select class="products form-select form-select-sm" name="model_id">
                                        <option>Select Job Order First</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="" class="mb-2">Department:</label>
                                    <select class="form-select form-select-sm" name="department_id">
                                        <option value="0">All</option>
                                        @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" @if($department->id == $filters['department_id']) selected @endif>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 d-print-none">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </form>

                        <hr>

                        <div class="row">
                            <div class="col-md-12 text-center p-2">
                                <h1>{{ $reportTitle }}</h1>
                            </div>
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
                                                    @foreach($departmentWiseBasicTimeResults as $result)
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
                                                    @foreach($departmentWiseBasicTimeResults as $result)
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
                                                    @foreach($departmentWiseOverTimeResults as $result)
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
                                                    @foreach($departmentWiseOverTimeResults as $result)
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
                            @endforeach

                            <div class="col-md-4 col-print-4">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered align-middle">
                                        <thead>
                                            <tr class="text-center bg-danger text-white">
                                                <th scope="col" colspan="3">Rework</th>
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
                                                    0
                                                </th>
                                                <th scope="col">
                                                    @php
                                                        $totalBasicTimeCost = 0;
                                                    @endphp
                                                    0
                                                </th>
                                            </tr>
                                            <tr>
                                                <th scope="col">OVER TIME</th>
                                                <th scope="col">
                                                    @php
                                                        $totalOverTime = 0;
                                                    @endphp
                                                    0
                                                </th>
                                                <th scope="col">
                                                    @php
                                                        $totalOverTimeCost = 0;
                                                    @endphp
                                                    0
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
                            <hr>

                            <div class="col-md-12 d-print-none">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>DATE</th>
                                                <th>MACHINE</th>
                                                <th>PART LIST</th>
                                                <th>TOTAL M3</th>
                                                <th>COLOR</th>
                                                <th>NO. OF WORKERS</th>
                                                <th>TESTING SPEED</th>
                                                <th>START TIME</th>
                                                <th>END TIME</th>
                                                <th>BREAK TIME</th>
                                                <th>DURATION</th>
                                                <th>BASIC TIME</th>
                                                <th>OVER TIME</th>
                                                <th>ACTUAL SPEED</th>
                                                <th>OUTPUT</th>
                                                <th>BASIC (RM)</th>
                                                <th>OT (RM)</th>
                                                <th>REAL COST</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalBasicTime = 0;
                                                $totalBasicCost = 0;
                                                $totalOTTime= 0;
                                                $totalOTCost = 0;
                                            @endphp
                                            @foreach($reportItems as $item)
                                                @foreach($item->progresses as $progress)
                                                    @php
                                                        $totalBasicTime += $progress->timer_type == 1 ? $progress->difference_seconds/60 : 0;
                                                        $totalBasicCost += ($progress->difference_seconds/60/60) * $item->workers[0]->worker->basic_salary;
                                                        $totalOTTime += $progress->timer_type == 1 ? $progress->difference_seconds/60 : 0;
                                                        $totalOTCost += ($progress->difference_seconds/60/60) * $item->workers[0]->worker->overtime_salary;
                                                    @endphp
                                                <tr>
                                                    <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        @foreach($item->machines as $machine)
                                                        {{ $machine->machine->name }}, 
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $item->stockCards[0]->stockCard->product->model_name }}</td>
                                                    <td>{{ ($item->stockCards[0]->stockCard->product->length * $item->stockCards[0]->stockCard->product->width * $item->stockCards[0]->stockCard->product->thick ) / 1000000000}}</td>
                                                    <td>{{ $item->stockCards[0]->stockCard->product->color_name }}</td>
                                                    <td>{{ count($item->workers) }}</td>
                                                    <td>{{ $item->testing_speed }}</td>
                                                    <td>{{ $progress->started_at }}</td>
                                                    <td>{{ $progress->ended_at }}</td>
                                                    <td>{{ $progress->timer_type == 2 ? number_format($progress->difference_seconds/60, 4) : '0' }}</td>
                                                    <td>{{ $progress->timer_type == 1 ? number_format($progress->difference_seconds/60, 4) : '0' }}</td>
                                                    <td>{{ number_format($progress->difference_seconds/60/60, 4) }}</td>
                                                    <td>{{ number_format($progress->difference_seconds/60/60, 4) }}</td>
                                                    <td>
                                                        {{ number_format(($progress->timer_type == 1 ? $progress->difference_seconds/60 : 0) / $item->total_quantity_produced ?? 1, 4) }}
                                                    </td>
                                                    <td>{{ $item->total_quantity_produced }}</td>
                                                    <td>
                                                        @php
                                                            $basicTimeCost = 0;
                                                        @endphp

                                                        @if($progress->started_at->format('H:i:s') >= $basicTimeStart && $progress->started_at->format('H:i:s') <= $basicTimeEnd)
                                                            {{ number_format(($progress->difference_seconds/60/60) * $item->workers[0]->worker->basic_salary, 4) }}
                                                            @php
                                                            $basicTimeCost = ($progress->difference_seconds/60/60) * $item->workers[0]->worker->basic_salary;
                                                            @endphp
                                                        @else
                                                        0
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $overTimeCost = 0;
                                                        @endphp
                                                        @if($progress->started_at->format('H:i:s') >= $overTimeStart)
                                                            {{ number_format(($progress->difference_seconds/60/60) * $item->workers[0]->worker->overtime_salary, 4) }}
                                                            @php
                                                            $overTimeCost = ($progress->difference_seconds/60/60) * $item->workers[0]->worker->overtime_salary;
                                                            @endphp
                                                        @else
                                                        0
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ number_format($basicTimeCost + $overTimeCost, 4) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

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

<!-- DataTables -->
<script src="{{ url('/assets') }}/plugins/DataTables/DataTables-1.10.25/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/DataTables-1.10.25/js/dataTables.bootstrap5.min.js"></script>

<!-- Utilities for Datatable -->
<script src="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/js/dataTables.buttons.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/js/buttons.bootstrap5.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/js/buttons.html5.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/js/buttons.print.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/js/buttons.colVis.min.js"></script>

<script src="{{ url('/assets') }}/plugins/DataTables/JSZip-2.5.0/jszip.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>

<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.js"></script>
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>

<script>
    $(document).ready(function() {
    var exportTitle = 'costing-daily-production-report-data-export-' + Date.now();
    var table = $('#dataTable').DataTable({
      "paging": true,
      "ordering": true,
       stateSave: true,
      "info": true,
      "autoWidth": false,
      "responsive": false,
      // prevent default automatic sort 
      "order": [],
      "pageLength": 50,
      "columnDefs": [
      ],
      buttons: [
          {
            extend: 'colvis',
            className: 'btn btn-secondary btn-sm mt-2',
            text: '<i class="fas fa-filter"></i> Columns',

          },
          {
              extend: 'copy',
              text: '<i class="fas fa-copy"></i> Copy All',
              className: 'btn btn-secondary btn-sm mt-2',
              exportOptions: {
                  columns: ':visible'
              },
              footer: true
          },
          {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Print all',
            title: exportTitle,
            className: 'btn-sm mt-2',
            exportOptions: {
              columns: ':visible'
            },
            footer: true
          },
          {
            extend: 'collection',
            text: '<i class="fas fa-download"></i> Export Data',
            className: 'btn btn-success btn-sm mt-2',
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> As CSV',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: exportTitle,
                    footer: true
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> As Excel',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: exportTitle,
                    footer: true
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> As PDF',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: exportTitle,
                    footer: true
                },
            ]
        }
      ],
      dom: 'lBfrtip'
    });

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
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last 3 Months': [moment().subtract(3,'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last 12 Months': [moment().subtract(12,'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        }
    }, function(start, end, label) {
        $('#dateStart').val(start.format('YYYY-MM-DD'));
        $('#dateEnd').val(end.format('YYYY-MM-DD'));
    });

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

  // Ajax Products for Job Orders
  $(".ajax-job-orders").change(function() {
    var jobOrderId = $(this).val();
    var routeUrl = "{{ route('job-orders.show', ':id')}}";
    routeUrl = routeUrl.replace(':id', jobOrderId);

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        var jobProductsOptions = "<option></option>";
        jobProductsList = response.job_products;
        currentJobOrderProducts = jobProductsList;
        $( jobProductsList ).each(function(index, jobProduct) {
          jobProductsOptions += "<option value='" + jobProduct.product_id + "'>" + jobProduct.product.model_name + " [ " + jobProduct.product.product_name + " ] " + jobProduct.product.length + "x" + jobProduct.product.width + "x" + jobProduct.product.thick + "</option>";
        });

        $(".products").html(jobProductsOptions);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });
  });
</script>
@endsection