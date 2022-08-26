@extends('layouts.app')

@section('custom_css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/datatables.min.css">
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/Buttons-1.7.1/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/DataTables-1.10.25/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/DataTables/Responsive-2.2.9/css/responsive.bootstrap5.min.css">
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
            <h1 class="m-0 text-dark"><i class="fas fa-tractor"></i> Daily Production</h1>
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
                    All Productions
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                    <thead class="bg-primary text-white">
                    <tr>
                      <th>ID</th>
                      <th>Stock Cards</th>
                      <th>Department</th>
                      <th>Workers</th>
                      <th>Machines</th>
                      <th>Total QTY Plan</th>
                      <th>Total QTY Produced</th>
                      <th>Total QTY Rejected</th>
                      <th>Testing Speed</th>
                      <th>Created At</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                      
                      @foreach ($productions as $production)
                      <tr>
                        <td>{{ $production->id }}</td>
                        <td>
                          @if(!$production->stockCards->isEmpty())
                              @foreach ($production->stockCards as $stockCard)
                              <b>{{ $stockCard->stockCard->stock_card_number }}</b>,
                              @endforeach
                          @else
                          N/A
                          @endif
                        </td>
                        <td>{{ $production->department->name }}</td>
                        <td>
                          @if(!$production->workers->isEmpty())
                              @foreach ($production->workers as $worker)
                              <b>{{ $worker->worker->name }}</b>,
                              @endforeach
                          @else
                          N/A
                          @endif
                        </td>
                        <td>
                          @if(!$production->machines->isEmpty())
                              @foreach ($production->machines as $machine)
                              <b>{{ $machine->machine->name }} [{{ $machine->machine->code }}]</b>,
                              @endforeach
                          @else
                          N/A
                          @endif
                        </td>
                        <td>{{ $production->total_quantity_plan }}</td>
                        <td>{{ $production->total_quantity_produced }}</td>
                        <td>{{ $production->total_quantity_rejected }}</td>
                        <td>{{ $production->testing_speed }}</td>
                        <td>{{ $production->created_at}}</td>
                        <td>{!! $production->is_ended ? '<span class="badge bg-success">Finished</span>' : '<span class="badge bg-secondary">Not Finished</span>' !!}</td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              <i class="fas fa-cogs"></i> Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li><a class="dropdown-item" href="{{ route('productions.edit', $production->id) }}">Edit</a></li>
                            </ul>
                          </div>

                        </td>
                      </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
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

<script>
  $(document).ready(function() {
    setInterval(()=>{window.location.reload()},300000);
    var exportTitle = 'machines-data-export-' + Date.now();
    var table = $('#dataTable').DataTable({
      "paging": true,
      "ordering": true,
       stateSave: true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      // sort by first column automatically
      "order": [[ 0, "desc" ]],
      "columnDefs": [
        // disable sorting on action column
        { orderable: false, targets: [11] }
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
              }
          },
          {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Print all',
            title: exportTitle,
            className: 'btn-sm mt-2',
            exportOptions: {
              columns: ':visible'
            }
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
                    title: exportTitle
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> As Excel',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: exportTitle
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> As PDF',
                    className: 'btn-sm',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: exportTitle
                },
            ]
        }
      ],
      dom: 'lBfrtip'
    });
  });

</script>
@endsection