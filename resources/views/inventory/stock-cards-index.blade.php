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
                    <i class="fas fa-print"></i> All Stock Cards <a href="{{ route('stock-cards.create') }}" class="btn btn-sm btn-primary float-end"><i
                        class="fa fa-plus"></i> Create new Stock Card</a>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                    <thead class="bg-primary text-white">
                    <tr>
                      <th>ID</th>
                      <th>Stock Card Number</th>
                      <th>Qty Ordered</th>
                      <th>Qty Received</th>
                      <th>Job Order</th>
                      <th>Product</th>
                      <th>Date In</th>
                      <th>Date Out</th>
                      <th>Rejected</th>
                      <th>Created At</th>
                      <th></th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($stockCards as $stockCard)
                      <tr>
                        <td>{{ $stockCard->id }}</td>
                        <td>{{ $stockCard->stock_card_number }}</td>
                        <td>{{ $stockCard->ordered_quantity }}</td>
                        <td>{{ $stockCard->available_quantity }}</td>
                        <td>
                          @if($stockCard->order_id !== null)
                          <a href="{{ route('job-orders.show', $stockCard->order_id) }}" target="_blank">{{ $stockCard->jobOrder->order_no_manual }}</a>
                          @else
                          N/A
                          @endif
                        </td>
                        <td><a href="{{ route('products.show', $stockCard->product_id) }}" target="_blank">{{ $stockCard->product->model_name }} [{{ $stockCard->product->product_name }}]</a></td>
                        <td>{{ $stockCard->date_in ?? "N/A"}}</td>
                        <td>{{ $stockCard->date_out ?? "N/A" }}</td>
                        <td>{{ $stockCard->is_rejected ? "Yes" : "No" }}</td>
                        <td>{{ $stockCard->created_at }}</td>
                        <td>
                          <a href="javascript:void(0)" class="btn btn-sm btn-primary generateStockCard"
                          data-order-id="{{ $stockCard->order_id }}"
                          data-product-id="{{ $stockCard->product_id }}"
                          data-date-in="{{ $stockCard->date_in }}"
                          data-is-rejected="{{ $stockCard->is_rejected ? "1" : "0" }}"
                          data-is-balance="{{ $stockCard->is_balance ? "1" : "0" }}"
                          ><i class="fas fa-print"></i> Print</a>
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
    var exportTitle = 'stock-cards-data-export-' + Date.now();
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
        { orderable: false, targets: [10] }
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

  $('body').on("click",'.generateStockCard', function() {
    var order_id = $(this).attr('data-order-id');
    var date_in = $(this).attr('data-date-in');
    var product_id = $(this).attr('data-product-id');
    var is_rejected = $(this).attr('data-is-rejected');
    var is_balance = $(this).attr('data-is-balance');
    w = 600;
    h = 650;
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);

    orderId = "0";
    if(order_id != '') {
      orderId = order_id;
    }

    window.open("{{ Url('') }}/job-order/receiving/stock-card-print/"+orderId+"/"+product_id+"?is_rejected=" + is_rejected + "&is_balance=" + is_balance + "&date_in=" + date_in, "Stock Card Print", "width="+w+", height="+h+", top="+top+", left="+left);
  });
</script>
@endsection