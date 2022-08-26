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
                <h1 class="m-0 text-dark"><i class="fas fa-calculator"></i> Time Summary Report</h1>
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
                            Generate Time Summary Report
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <form class="row g-3 mb-4" method="POST" action="">
                            @csrf
                            <div class="col-md-3">
                                <label for="dates-range" class="form-label">Date Range</label>
                                <input type="text" class="form-control form-control-sm" id="dates-range" value="" />
                                <input type="hidden" name="dateStart" id="dateStart" value="{{ $filters['dateRanges']['start'] ?? '' }}">
                                <input type="hidden" name="dateEnd" id="dateEnd" value="{{ $filters['dateRanges']['end'] ?? '' }}">
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
                            <div class="col-md-12 d-print-none">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </form>

                        <hr>

                        <div class="row">

                            <div class="col-md-12 text-center p-2">
                                <h1>{{ $reportTitle }}</h1>
                            </div>

                            <div class="col-md-12">
                                <h2>COST (REPORT)</h2>
                                <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Item</th>
                                            @foreach($departments as $department)
                                            <th>{{ $department->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportItems as $item)
                                            @foreach($item->progresses as $progress)
                                            <tr>
                                                <td>[{{ $item->stockCards[0]->stockCard->product->model_name }}] {{ $item->stockCards[0]->stockCard->product->product_name }}</td>
                                                @foreach($departments as $department)
                                                    @if($item->department_id == $department->id)
                                                    <td>{{ number_format((($progress->difference_seconds/60/60) * $item->workers[0]->worker->basic_salary) + ($progress->difference_seconds/60/60) * $item->workers[0]->worker->overtime_salary, 4) }}</td>
                                                    @else
                                                    <td>0</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- SELECT dp.department_id,(SELECT departments.name from departments where departments.id = dp.department_id) as deparment, (SELECT SUM(u.basic_salary) as salary from users u where u.id in (SELECT dpw.worker_id from daily_production_workers dpw where dpw.daily_production_id = dp.id)) as salary FROM `daily_productions` dp inner join daily_production_stock_cards dpsc on dp.id = dpsc.daily_production_id inner join product_stock_cards psc on dpsc.stock_card_id = psc.id group by psc.product_id; -->

                            <div class="col-md-12">
                                <hr>
                                
                                <h2>TIME USED - MINUTES (REPORT)</h2>
                                <table class="table table-striped dt-responsive nowrap" id="dataTable2" style="width:100%">
                                   <thead class="bg-primary text-white">
                                        <th>Item</th>
                                        @foreach($departments as $department)
                                        <th>{{ $department->name }}</th>
                                        @endforeach
                                    </thead>
                                    <tbody>
                                        @foreach($reportItems as $item)
                                            @foreach($item->progresses as $progress)
                                            <tr>
                                                <td>[{{ $item->stockCards[0]->stockCard->product->model_name }}] {{ $item->stockCards[0]->stockCard->product->product_name }}</td>
                                                @foreach($departments as $department)
                                                    @if($item->department_id == $department->id)
                                                    <td>{{ $progress->timer_type == 1 ? number_format($progress->difference_seconds/60, 4) : '0' }}</td>
                                                    @else
                                                    <td>0</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
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

<script src="{{ url('/assets') }}/plugins/DataTables/Responsive-2.2.9/js/dataTables.responsive.min.js"></script>
<script src="{{ url('/assets') }}/plugins/DataTables/Responsive-2.2.9/js/responsive.bootstrap5.min.js"></script>

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

    $(document).ready(function() {
    var exportTitle = 'time-summary-report-data-export-' + Date.now();
    var table = $('#dataTable, #dataTable2').DataTable({
      "paging": true,
      "ordering": true,
       stateSave: true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
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