@extends('layouts.app')

@section('custom_css')
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.css" />
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
                            <i class="fas fa-print"></i> Create Stock Card <a href="{{ route('stock-cards.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="post"
                                    action="{{ route('stock-cards.store') }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @csrf

                                            <div class="form-group">
                                                <label for="ordered_quantity">Quantity <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="ordered_quantity" value="{{ old('ordered_quantity', 1) }}"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label for="product_id">Product <span class="required">*</span></label>
                                                <select class="select2AutoFocus ajax-products" class="form-select" name="product_id" required>
                                                    <option></option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="date_in">Date In <span class="required">*</span></label>
                                                <input type="text" class="form-control datepicker" id="date_in" name="date_in" value="{{ old('date_in') }}" required>
                                            </div>

                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary mt-2"><i
                                                        class="fas fa-save"></i> Submit</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>

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
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.js"></script>

<script>
    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    }, function(start, end, label) {
        // console.log(start.format('YYYY-MM-DD'));
    });
    // Re-Initialize Select2 of All products search
    // Ajax Select2 Products
    var ajaxProductsOptions = {
        "language": {
            "noResults": function() {
            return "No Results Found... <a href='{{ route('products.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-cubes'></i> Add New Product</a>";
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        width: '100%',
        placeholder: 'Search Product',
        minimumInputLength: 1,
        ajax: {
            url: '{{ route('products.search') }}',
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
    $('.ajax-products').select2(ajaxProductsOptions);
</script>
@endsection