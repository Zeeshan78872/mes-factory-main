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
                <h1 class="m-0 text-dark"><i class="fas fa-warehouse"></i> Manage Inventory</h1>
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
                            Generate Inventory Report
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <form class="row g-3 mb-4" method="POST" action="">
                            @csrf
                            <div class="col-md-3">
                                <label for="dates-range" class="form-label">Date Range</label>
                                <input type="text" class="form-control" id="dates-range" value="" />
                                <input type="hidden" name="dateStart" id="dateStart"
                                    value="{{ $filters['dateRanges']['start'] ?? '' }}">
                                <input type="hidden" name="dateEnd" id="dateEnd"
                                    value="{{ $filters['dateRanges']['end'] ?? '' }}">
                            </div>
                            <div class="col-md-12 d-print-none">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </form>

                        <hr>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>Model</th>
                                                <th>Item</th>
                                                <th>Size</th>
                                                <th>Bring Forward</th>
                                                @for ($i = 0; $i < $totalDates; $i++)
                                                    @if (!isset($results[$datesRange[$i]]['in']['qty']))
                                                        @php
                                                            continue;
                                                        @endphp
                                                    @endif
                                                    <th>
                                                        &#8205;{{ $datesRange[$i] }}
                                                    </th>
                                                @endfor
                                                <th>Total In</th>
                                                @for ($i = 0; $i < $totalDates; $i++)
                                                    @if (!isset($results[$datesRange[$i]]['out']['qty']))
                                                        @php
                                                            continue;
                                                        @endphp
                                                    @endif
                                                    <th>
                                                        &#8205;{{ $datesRange[$i] }}
                                                    </th>
                                                @endfor
                                                <th>Total Out</th>
                                                <th>Balance</th>
                                                <th>Site</th>
                                                <th>Location</th>
                                                <th>Department</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 0;
                                                $lastStockId = 0;
                                            @endphp
                                            @for ($i = 0; $i < $totalDates; $i++)
                                            @if (!isset($results[$datesRange[$i]]))
                                                @php
                                                    continue;
                                                @endphp
                                            @endif
                                            @php
                                                $currentStockId = isset($results[$datesRange[$i]]['in']['stock_card_id']) ? $results[$datesRange[$i]]['in']['stock_card_id'] : $results[$datesRange[$i]]['out']['stock_card_id'];
                                            @endphp
                                            @if ($currentStockId == $lastStockId)
                                                @php
                                                    continue;
                                                @endphp
                                            @else
                                                @php
                                                    $lastStockId = $currentStockId;
                                                @endphp
                                            @endif
                                            @php
                                                $counter++;   
                                                $bringForwardAmount = isset($bringForwardResults[$counter]['amount']) ? $bringForwardResults[$counter]['amount'] : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ isset($results[$datesRange[$i]]['in']['product']->product) ? $results[$datesRange[$i]]['in']['product']->product->model_name : '' }}
                                                </td>
                                                <td>
                                                    {{ isset($results[$datesRange[$i]]['in']['product']->product) ? $results[$datesRange[$i]]['in']['product']->product->product_name : '' }}
                                                </td>
                                                <td>
                                                    {{ isset($results[$datesRange[$i]]['in']['product']->product) ? $results[$datesRange[$i]]['in']['product']->product->length . 'x' . $results[$datesRange[$i]]['in']['product']->product->width . 'x' . $results[$datesRange[$i]]['in']['product']->product->thick : '' }}
                                                </td>
                                                <td>{{ $bringForwardAmount }}</td>
                                                @php
                                                    $totalIn = 0;
                                                    $totalOut = 0;
                                                @endphp
                                                @for ($j = 0; $j < $totalDates; $j++)
                                                    @if (!isset($results[$datesRange[$j]]['in']['qty']))
                                                        @php
                                                            continue;
                                                        @endphp
                                                    @endif
                                                    @php
                                                        $totalIn += isset($results[$datesRange[$j]]['in']['qty']) ? $results[$datesRange[$j]]['in']['qty'] : 0;
                                                    @endphp
                                                    <td>
                                                        {{ isset($results[$datesRange[$j]]['in']['qty']) ? $results[$datesRange[$j]]['in']['qty'] : 0 }}
                                                    </td>
                                                @endfor
                                                <td>{{ $totalIn }}</td>

                                                @for ($j = 0; $j < $totalDates; $j++)
                                                    @if (!isset($results[$datesRange[$j]]['out']['qty']))
                                                        @php
                                                            continue;
                                                        @endphp
                                                    @endif
                                                    @php
                                                        $totalOut += isset($results[$datesRange[$j]]['out']['qty']) ? $results[$datesRange[$j]]['out']['qty'] : 0;
                                                    @endphp
                                                    <td>
                                                        {{ isset($results[$datesRange[$j]]['out']['qty']) ? $results[$datesRange[$j]]['out']['qty'] : 0 }}
                                                    </td>
                                                @endfor
                                                <td>{{ $totalOut }}</td>
                                                <td>{{ $bringForwardAmount + $totalIn - $totalOut }}</td>
                                                <td>
                                                    {{ isset($results[$lastOutDate]['out']['site']) ? $results[$lastOutDate]['out']['site']->name : '' }}
                                                </td>
                                                <td>
                                                    {{ isset($results[$lastOutDate]['out']['site']) ? $results[$lastOutDate]['out']['location']->name : '' }}
                                                </td>
                                                <td>
                                                    {{ isset($results[$lastOutDate]['out']['site']) ? $results[$lastOutDate]['out']['department']->name : '' }}
                                                </td>
                                            </tr>
                                            @endfor
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

<script>
    $(document).ready(function() {
    var exportTitle = 'inventory-report-data-export-' + Date.now();
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

</script>
@endsection