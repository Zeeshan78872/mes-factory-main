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
            <h1 class="m-0 text-dark"><i class="fas fa-cubes"></i> Manage Products</h1>
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
                    All Products <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary float-end"><i class="fa fa-plus"></i> Add New</a>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-striped dt-responsive nowrap" id="dataTable" style="width:100%">
                    <thead class="bg-primary text-white">
                    <tr>
                      <th>ID</th>
                      <th>Parent Product</th>
                      <th>Category</th>
                      <th>Item</th>
                      <th>Product Number</th>
                      <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                      
                      @foreach ($products as $product)
                      <tr>
                        <td><a href="{{ route('products.show', $product->id) }}">{{ $product->id }}</a></td>
                        <td>
                          @if(empty($product->parentProduct))
                            -
                          @else
                            [{{ $product->parentProduct->model_name . "] " . $product->parentProduct->product_name }}
                          @endif
                        </td>
                        <td>{{ $product->category->name ?? "N/A" }}</td>
                        <td>[{{ $product->model_name }}]</td>
                        <td>{{ $product->product_name }}</td>
                        <td>
                          <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              <i class="fas fa-cogs"></i> Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li><a class="dropdown-item" href="{{ route('products.show', $product->id) }}">View</a></li>
                              <li><a class="dropdown-item" href="{{ route('products.edit', $product->id) }}">Edit</a></li>

                              <li><a class="dropdown-item" href="{{ route('products.bom.mapping', $product->id) }}">Create BOM List</a></li>

                              <li><a class="dropdown-item" href="{{ route('products.create') }}?productId={{ $product->id }}&preFillType=color">Add Color Variant</a></li>
                              <li><a class="dropdown-item" href="{{ route('products.create') }}?productId={{ $product->id }}&preFillType=size">Add Size Variant</a></li>

                              <li><a class="dropdown-item" href="#!" onclick="callDeletItem('{{ $product->id }}', 'products');">Delete</a></li>
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
    var exportTitle = 'products-data-export-' + Date.now();
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
        { orderable: false, targets: [5] }
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