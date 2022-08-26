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
                <h1 class="m-0 text-dark"><i class="fas fa-tasks"></i> Quality Assurance Report</h1>
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
                            Generate QA Inspections Report
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        
                        <form class="row g-3 mb-4" method="POST" action="">
                            @csrf
                            <div class="col-md-3">
                                <label for="dates-range" class="form-label">Date Range</label>
                                <input type="text" class="form-control" id="dates-range" value="" />
                                <input type="hidden" name="dateStart" id="dateStart" value="{{ $filters['dateRanges']['start'] ?? '' }}">
                                <input type="hidden" name="dateEnd" id="dateEnd" value="{{ $filters['dateRanges']['end'] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label for="user" class="form-label">Inspection By</label>
                                <select class="form-select" name="inspection_by" id="inspection_by">
                                    <option value="0">All Users</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if($user->id == $filters['user']) selected @endif>{{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="qa_type" class="form-label">QA Type</label>
                                <select class="form-select" name="qa_type" id="qa_type">
                                    @foreach($QATypes as $QAType)
                                        @if ($loop->index === 0)
                                            @php
                                                continue;
                                            @endphp
                                        @endif
                                    <option value="{{ $loop->index }}" @if($filters['qa_type']==$loop->index) selected @endif>{{ $QAType }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="report_type" class="form-label">Report Type</label>
                                <select class="form-select" name="report_type" id="report_type">
                                    <option value="0" @if($filters['report_type'] == 0) selected @endif>Daily</option>
                                    <option value="1" @if($filters['report_type'] == 1) selected @endif>Monthly</option>
                                    <option value="2" @if($filters['report_type'] == 2) selected @endif>Summary</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </div>
                        </form>

                        <hr>

                        {{-- Daily REPORT --}}
                        @if($filters['report_type'] == 0)
                        <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Inspection By</th>
                                    <th>PO NO</th>
                                    <th>JOB ORDER</th>
                                    <th>MODEL</th>
                                    <th>PARTLIST</th>
                                    <th>COLOR</th>
                                    <th>STAGE</th>
                                    <th>DEFECT CRITERIA</th>
                                    <th>DEFECT CATEGORY</th>
                                    <th>TOTAL QTY</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter=1;
                                @endphp
                                @foreach ($reportItems as $item)
                                    @foreach ($item->answers as $answer)
                                    <tr>
                                        <td>{{ $counter }}</td>
                                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $item->inspectionBy->name }}</td>
                                        <td>{{ $item->stockCard->jobOrder->po_no }}</td>
                                        <td>{{ $item->stockCard->jobOrder->order_no_manual }}</td>
                                        <td>{{ $item->stockCard->product->product_name }}</td>
                                        <td>{{ $item->stockCard->product->model_name }}</td>
                                        <td>{{ $item->stockCard->product->color_name }}</td>
                                        <td>{{ $QATypes[$item->qa_type] }}</td>
                                        <td>{{ $answer->question->question }}</td>
                                        <td>{{ $answer->question->defect_category }}</td>
                                        <td>{{ $answer->cr + $answer->mi + $answer->mn }}</td>
                                        <td>{{ $QAOptions[$answer->answer] }}</td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                        {{-- Daily REPORT --}}

                        {{-- Monthly REPORT --}}
                        @if($filters['report_type'] == 1)
                        <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Stage</th>
                                    <th>Defect Category</th>
                                    <th>Defect Criteria</th>
                                    @for ($i = 0; $i < $totalMonths; $i++)
                                        <th>{{ strtoupper((new \Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("M")) . '-' . (new \Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("Y") }}</th>
                                    @endfor
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $questionMonthlyTotalSumResults = [];
                                    $questionCategoriesTotalSumResults = [];
                                @endphp
                                @foreach($reportItems['questions'] as $question)
                                    @php
                                    $totalForThisQuestion = 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $QATypes[$filters['qa_type']] }}</td>
                                        <td>{{ $question->defect_category ?? 'N/A' }}</td>
                                        <td>{{ $question->question }}</td>
                                        @for ($i = 0; $i < $totalMonths; $i++)
                                            <td>
                                                @php
                                                    $currentQuestionId = $question->id;
                                                    $currentMonth = (new \Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("m");
                                                    $currentYear = (new Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("Y");

                                                    $answersArr = $reportItems['answers'];
                                                    $currentMonthAnswersIndex = null;

                                                    foreach($answersArr as $key => $val) {
                                                        if($val['month'] == $currentMonth && $val['year'] == $currentYear) {
                                                            $currentMonthAnswersIndex = $key;
                                                            break;
                                                        }
                                                    }
                                                    if($currentMonthAnswersIndex === null) {
                                                        echo '0';
                                                        continue;
                                                    }

                                                    $currentMonthAnswers = $reportItems['answers'][$currentMonthAnswersIndex]['answers'];
                                                    $currentMonthTotalQty = 0;

                                                    foreach($currentMonthAnswers as $key => $val) {
                                                        if($val['qa_form_question_id'] == $currentQuestionId) {
                                                            $currentMonthTotalQty = $val['total_qty'];
                                                            $totalForThisQuestion += $val['total_qty'];
                                                            break;
                                                        }
                                                    }

                                                    echo str_replace('.00', '', $currentMonthTotalQty);
                                                @endphp
                                            </td>

                                            @php
                                                if(isset($questionMonthlyTotalSumResults[$i])) {
                                                    $questionMonthlyTotalSumResults[$i]['total_qty'] += $totalForThisQuestion;
                                                } else {
                                                    $questionMonthlyTotalSumResults[$i]['total_qty'] = $totalForThisQuestion;
                                                }

                                                if(isset($questionCategoriesTotalSumResults[$i][$question->defect_category])) {
                                                    $questionCategoriesTotalSumResults[$i][$question->defect_category]['total_qty'] += $totalForThisQuestion;
                                                } else {
                                                    $questionCategoriesTotalSumResults[$i][$question->defect_category]['total_qty'] = $totalForThisQuestion;
                                                }
                                            @endphp
                                        @endfor
                                        <td>{{ str_replace('.00', '', $totalForThisQuestion) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-info">
                                    <td colspan="3" class="text-center fw-bold">SUM OF DEFECT FOUND</td>
                                    @php
                                        $totalSumOfDefects = 0;   
                                    @endphp
                                    @for ($i = 0; $i < $totalMonths; $i++)
                                        <td>
                                            {{ str_replace('.00', '', $questionMonthlyTotalSumResults[$i]['total_qty']) }}  
                                        </td>
                                        @php
                                            $totalSumOfDefects += $questionMonthlyTotalSumResults[$i]['total_qty'];
                                        @endphp
                                    @endfor
                                    <td>{{ str_replace('.00', '', $totalSumOfDefects) }}</td>
                                </tr>

                                @foreach($reportItems['questionCategories'] as $questionCategory)
                                    @php
                                        $totalSumOfDefectsByCategories = 0;
                                    @endphp
                                    <tr>
                                        <td colspan="3" class="text-center fw-bold">{{ $questionCategory ?? 'N/A' }}</td>
                                        @for ($i = 0; $i < $totalMonths; $i++)
                                            <td>
                                             {{ $questionCategoriesTotalSumResults[$i][$questionCategory]['total_qty'] }}
                                            </td>
                                            @php
                                                $totalSumOfDefectsByCategories = $questionCategoriesTotalSumResults[$i][$questionCategory]['total_qty'];
                                            @endphp
                                        @endfor
                                        <td>{{ str_replace('.00', '', $totalSumOfDefectsByCategories) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-info">
                                    <td colspan="3" class="text-center fw-bold">MONTHLY TOTAL RECEIVING</td>
                                    @for ($i = 0; $i < $totalMonths; $i++)
                                        <td>
                                        0
                                        </td>
                                    @endfor
                                    <td>0</td>
                                </tr>
                            </tfoot>
                        </table>
                        @endif
                        {{-- Monthly REPORT --}}

                        {{-- Summary REPORT --}}
                        @if($filters['report_type'] == 2)
                        <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>MONTH</th>
                                    <th>IQC QTY</th>
                                    <th>IPQC QTY</th>
                                    <th>FQC QTY</th>

                                    <th>IQC %</th>
                                    <th>IPQC %</th>
                                    <th>FQC %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 0; $i < $totalMonths; $i++)
                                    @php
                                    $currentMonth = (new \Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("n");   
                                    $currentYear = (new \Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("Y");   
                                    @endphp
                                    <tr>
                                        <td class="fw-bold">
                                            {{ strtoupper((new \Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("F")) . '-' . (new \Carbon\Carbon($filters['dateRanges']['start']))->addMonths($i)->format("Y") }}
                                        </td>
                                        <td>
                                            {{ $reportItems[$currentYear][$currentMonth][1] ?? 0 }}
                                        </td>
                                        <td>
                                            {{ $reportItems[$currentYear][$currentMonth][2] ?? 0 }}
                                        </td>
                                        <td>
                                            {{ $reportItems[$currentYear][$currentMonth][3] ?? 0 }}
                                        </td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                        @endif
                        {{-- Monthly REPORT --}}
                      
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
    var exportTitle = 'qa-report-data-export-' + Date.now();
    var table = $('#dataTable').DataTable({
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

</script>
@endsection