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
                    <i class="fas fa-pallet"></i> All Inventory (Issued for Production) <a href="{{ route('inventory.issue.for.production') }}" class="btn btn-sm btn-primary float-end"><i class="fa fa-plus"></i> Issue New Item</a>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                    <thead class="bg-primary text-white">
                    <tr>
                      <th>ID</th>
                      <th>Stock Card</th>
                      <th>Quantity</th>
                      <th>Site</th>
                      <th>Location</th>
                      <th>Department</th>
                      <th>Machine</th>
                      <th>Issued At</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                      
                      @foreach ($inventoryIssuedForProductions as $item)
                      <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->stockCard->id }} [{{ $item->stockCard->stock_card_number }}]</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->site->name }} [{{ $item->site->code }}]</td>
                        <td>{{ $item->location->name }} [{{ $item->location->code }}]</td>
                        <td>{{ $item->department->name }} [{{ $item->department->code }}]</td>
                        <td>{{ $item->machine->name }} [{{ $item->machine->code }}]</td>
                        <td>{{ $item->created_at }}</td>
                        <td><a href="{{ route('inventory.return.from.production', $item->id) }}" class="btn btn-sm btn-danger">Return Item</a></td>
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

    <form action="#" method="post" id="deletItemForm" style="display: none;">
        @csrf
        {{ method_field('DELETE') }}
    </form>
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
    var exportTitle = 'inventory-issued-for-production-data-export-' + Date.now();
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
        // { orderable: false, targets: [7] }
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