@extends('layouts.app')

@section('custom_css')
    <link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.css" />
    <style>
        .BOMProductsList thead th {
            min-width: 150px;
            border: 1px solid #b7b7b7;
        }
        .BOMProductsList th:nth-child(2), .BOMProductsList td:nth-child(2) {
            position: sticky;
            left: 0;
            background-color: #e2e2e2;
            z-index: 1;
            box-shadow: -4px 0px 3px 0px #00000038 inset;
        }
        .BOMProductsList thead tr:nth-child(2) {
            position: sticky;
            top: 0;
            background-color: #e2e2e2;
            z-index: 2;
            box-shadow: 0px 0px 10px 0px #333;
        }
        #multi-product-table thead td input{
          width: 148px;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-cart-plus"></i> Manage Purchase Order List</h1>
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
                            <h4 class="card-title">Update Purchase Order List
                                <a href="{{ route('job-order.purchase.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                                    Back</a></h4>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Search Job Order:</label>
                                        <select class="select2AutoFocus ajax-job-orders form-select">
                                            <option></option>
                                            @if(!empty($jobOrder))
                                                <option value="{{ $jobOrder->id }}" selected> #{{ $jobOrder->id }} [{{ $jobOrder->order_no_manual }}]</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Select Job Order Product Model:</label>
                                        <select class="job-orders-products form-select form-select-sm">
                                            @if(empty($jobOrder))
                                                <option>Select Job Order First</option>
                                            @endif

                                            @if(!empty($jobOrder))
                                                <option></option>
                                                @foreach($jobOrder->jobProducts as $jobProducts)
                                                    <option data-model="{{ $jobProducts->product->model_name }}" data-qty="{{ $jobProducts->quantity }}" value="{{ $jobProducts->product->id }}" @if($jobProducts->product->id == $productId) selected @endif>{{ $jobProducts->product->model_name }} [{{ $jobProducts->product->product_name }}]</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 productDetails d-none mt-3">
                                    <h4>Job Order Product Details:</h4>
                                    <ul></ul>

                                    @php
                                        $isBomModelCombine = 0;
                                    @endphp
                                    @if(!empty($jobOrder))
                                        @php
                                            $isBomModelCombine = $jobOrder->combine_models_bom;
                                        @endphp
                                    @endif

                                    <input class="form-check-input combine_models_bom_checkbox ms-2" type="checkbox" name="combine_models_bom" value="1" {{ $isBomModelCombine==1 ? 'checked' : '' }} disabled> Combine Model BOM
                                </div>

                                <div class="col-md-12 bomListBox d-none">
                                    <hr>

                                    <h4>Add More Items in Purchase List:</h4>
                                    <div class="form-group">
                                        <label for="">Search Items:</label>
                                        <select class="select2AutoFocus ajax-products form-select">
                                            <option></option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-1">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="addToBom()"><i class="fa fa-plus"></i> Add</button>
                                        <button data-bs-toggle="modal" data-bs-target="#addMultipleProducts" id="AddItemsInTable_" class="btn btn-sm btn-primary">Add Multiple Items</button>

                                      </div>

                                    <hr>
                                    <h4>Purchase List:</h4>
                                    <hr>

                                    <div class="row p-2" style="background: #eee; border: 1px solid #999;">

                                        <div class="col-md-3">
                                            <input class="form-check-input same_est_delievery_checkbox ms-2" type="checkbox" name="same_est_delievery" value="1"> Same EST delivery Dates
                                            <input type="text" class="form-control datepicker same_est_delievery_input" value="">
                                        </div>

                                        <div class="col-md-3">
                                            <input class="form-check-input same_po_no_checkbox ms-2" type="checkbox" name="same_po_no" value="1">
                                            Same PO NO
                                            <input type="text" class="form-control same_po_no_input" value="">
                                        </div>

                                        <div class="col-md-3">
                                            <input class="form-check-input same_supplier_checkbox ms-2" type="checkbox" name="same_supplier" value="1">
                                            Same Supplier

                                            <select class="form-select same_supplier_input">
                                                <option value=""></option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input class="form-check-input same_date_order_checkbox ms-2" type="checkbox" name="same_date_order" value="1">
                                            Same Date Order
                                            <input type="text" class="form-control datepicker same_date_order_input" value="">
                                        </div>

                                    </div>

                                    <hr>


                                    <form method="post" action="{{ route('job-order.purchase.store') }}">
                                        @csrf

                                        <div class="table-responsive BOMProductsList">
                                            <table class="table table-striped table-hover table-bordered align-middle">
                                                <thead>
                                                <tr class="text-center">
                                                    <th scope="col" colspan="29" class="bg-primary text-white fs-5">PURCHASE USES</th>
                                                    <th scope="col" colspan="10" class="bg-secondary fs-5 text-white">SUBCON</th>
                                                </tr>
                                                <tr>
                                                    <th scope="col">Item</th>
                                                    <th scope="col">Part Number</th>
                                                    <th scope="col">Material</th>
                                                    <th scope="col">Length & Unit</th>
                                                    <th scope="col">Width & Unit</th>
                                                    <th scope="col">Thick & Unit</th>
                                                    <th scope="col">QTY</th>
                                                    <th scope="col">Total QTY</th>

                                                    <th scope="col">(BOM) Length</th>
                                                    <th scope="col">(BOM) Width</th>
                                                    <th scope="col">(BOM) Thick</th>
                                                    <th scope="col">(BOM) QTY</th>

                                                    <th scope="col">FOLLOW QTY ORDER BY R&D</th>

                                                    <th scope="col">Purchase Length</th>
                                                    <th scope="col">Purchase Width</th>
                                                    <th scope="col">Purchase Thick</th>
                                                    <th scope="col">Purchase QTY</th>

                                                    <th scope="col">Loc. Receive</th>
                                                    <th scope="col">Loc. Produce</th>
                                                    <th scope="col">Loc. Loading</th>
                                                    <th scope="col">Stock Card</th>
                                                    <th scope="col">QTY Balance</th>
                                                    <th scope="col">Date Order</th>
                                                    <th scope="col">PO NO</th>
                                                    <th scope="col">QTY Order</th>
                                                    <th scope="col">Price Per Unit</th>
                                                    <th scope="col">Supplier Name</th>
                                                    <th scope="col">EST. Delivery Date</th>
                                                    <th scope="col">Remarks</th>
                                                    <th scope="col">Send To SUBCON</th>
                                                    <th scope="col">Date Out</th>
                                                    <th scope="col">PO No</th>
                                                    <th scope="col">QTY</th>
                                                    <th scope="col">Price Per Unit</th>
                                                    <th scope="col">Supplier Name</th>
                                                    <th scope="col">EST. Delivery Date</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Remarks</th>
                                                    <th scope="col" style="min-width: 86px;">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-2">
                                            <input type="hidden" class="form-control" name="order_id" id="bom_order_id" value="0">
                                            <input type="hidden" class="form-control" name="product_id" id="bom_product_id" value="0">
                                            <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Save</button>
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

    <!-- Modal -->
    <div class="modal fade" id="stockCardSelectorModal" tabindex="-1" aria-labelledby="stockCardSelectorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockCardSelectorModalLabel">Select Stock Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive stockCardsListTable">
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Card Number</th>
                                <th scope="col">Balance Qty</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="SelectMultipleProducts" tabindex="-1" aria-labelledby="SelectMultipleProductsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockCardSelectorModalLabel">Select Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="table-responsive stockCardsListTable">
                        <table class="table table-striped table-hover table-bordered align-middle">
                            <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Card Number</th>
                                <th scope="col">Balance Qty</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addMultipleProducts" tabindex="-1" aria-labelledby="addMultipleProducts" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanQRCodeStockCardProductionModal1Label">Select Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="multi-product-table" class="table table-bordered table-striped" style="width:100%;">
        
                 
                 
                <thead>
                        <tr>
                            <th><input onclick="$('#multi-product-table tbody input[type=checkbox]').prop('checked',this.checked)" type="checkbox" id="selectAll"></th>
                            <th>Product Number</th>
                            <th>Item</th>
                            <th>Material</th>
                            <th>Category</th>
                            <th style="display:none;">ID</th>
                        </tr>

                        <tr>
                    <th></th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="display: none;"></td>
                  </tr>
                    </thead>
                    <tbody>
                    <!-- <pre>
                        {{--print_r($purchaseItemList->toArray());--}}
                      </pre> -->
                    @foreach($purchaseItemList as $_data)

                        <tr>
                            <td><input value="{{ $_data->id }}" type="checkbox"></td>
                            <td id="prod_no">{{$_data->product_name}}</td>
                            <td id="model_name">{{$_data->model_name}}</td>
                            <td id="material">{{$_data->material}}</td>
                            <td>{{\App\Models\ProductCategory::where(['id'=>$_data->category_id])->first()->name}}</td>
                            <td id="id_prod" style="display:none;">{{$_data->id}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button id="AddItemsInTable" class="btn btn-success" data-bs-dismiss="modal">Add Items</button>
            </div>
        </div>
    </div>
</div>
@endsection



  
@section('custom_js')
    <script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
    <script type="text/javascript" src="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.js"></script>
    <script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
    <script>
        var jobProductsList = null;
        var combineQtyForCalculation=0;
        function initDatePicker() {
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(start, end, label) {
                // console.log(start.format('YYYY-MM-DD'));
            });
        }

        // Suppliers
        var supplierHtml = '';
        var supplierArr = [];
        @foreach ($suppliers as $supplier)
        supplierArr.push('{{ $supplier->name }}');
        supplierHtml += `<option value="{{ $supplier->name }}">{{ $supplier->name }}</option>`;
        @endforeach

        // Units
        var unitsHtml = '';
        var unitsArr = [];
        @foreach ($units as $unit)
        unitsArr.push('{{ $unit->name }}');
        unitsHtml += `<option value="{{ $unit->name }}">{{ $unit->name }}</option>`;
        @endforeach

        var currentJobOrderProducts = null;
        var jobOrderProductDetails = null
        // Ajax Select2 Products
        var ajaxProductsOptions = {
            "language": {
                "noResults": function() {
                    return "No Results Found... <a href='{{ route('products.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-cubes'></i> Add New Item</a>";
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            width: '100%',
            placeholder: 'Search Items',
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
                        jobProductsOptions += "<option data-model='" + jobProduct.product.model_name + "' data-qty='" + jobProduct.quantity + "' value='" + jobProduct.product_id + "'>" + jobProduct.product.model_name + " [ " + jobProduct.product.product_name + " ] " + jobProduct.product.length + "x" + jobProduct.product.width + "x" + jobProduct.product.thick + "</option>";
                    });

                    $(".job-orders-products").html(jobProductsOptions);

                    @if(!empty($jobOrder))
                    $('.job-orders-products').val('{{ $productId }}');
                    $('.job-orders-products').trigger("change");
                    @endif
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
                }
            });
        });

        // Populate Bom Items for Selected Job Order Model
        $(".job-orders-products").change(function() {
            var selectedJobOrder = $(".ajax-job-orders").val();
            var selectedProduct = $(".job-orders-products").val();

            // Find Job Order Product Details
            $.each(currentJobOrderProducts, function( index, productOrder ) {
                if( productOrder.product_id == selectedProduct && productOrder.order_id == selectedJobOrder ) {
                    jobOrderProductDetails = productOrder;
                    return false;
                }
            });

            // Prepare Job Order Product Details
            var productDetailHtml = `
                    <li><b>Model</b>: ${jobOrderProductDetails.product.model_name}</li>
                    <li><b>Product Name</b>: ${jobOrderProductDetails.product.product_name}</li>
                    <li><b>Job Order Quantity</b>: ${jobOrderProductDetails.quantity}</li>
                    `;

                            // Show Job Order Product Details
                            $(".productDetails ul").html(productDetailHtml);
                            $(".productDetails").removeClass('d-none');

                            // Show BOM List Box
                            $(".bomListBox").removeClass('d-none');

                            // Update Hidden Fields
                            $("#bom_order_id").val(selectedJobOrder);
                            $("#bom_product_id").val(selectedProduct);

                            // Get Job Order BOM List Items
                            var routeUrl = "{{ route('job-order.purchase.list', ['order_id'=> ':order_id', 'product_id'=> ':product_id']) }}";
                            routeUrl = routeUrl.replace(':order_id', selectedJobOrder);
                            routeUrl = routeUrl.replace(':product_id', selectedProduct);

                            $.ajax({
                                url: routeUrl,
                                type: "get",
                                success: function (response) {
                                    var lastCat = 0;
                                    var lastParentProductId = 0;
                                    $('.BOMProductsList tbody').html('');

                                    $.each(response, function( index, bomItem ) {
                                        var categoryHeading = '';
                                        var bomListHtmlData = '';

                                        if(bomItem.item.category.has_bom_items) {
                                            var isBomCat = true;
                                            if(bomItem.item.category_id !== lastCat) {
                                                // BOM Category Heading
                                                categoryHeading += `<tr data-bom-cat-id="${bomItem.item.bomcategory_id}" class="rowHeading">
                                                <td colspan="36" class="fs-5 text-white text-center bg-info">
                                                  <b>${bomItem.item.category.name}</b>
                                                </td>
                                              </tr>`;
                                            }
                                            lastCat = bomItem.item.bomcategory_id;
                                        }
                                        if(bomItem.item.parent_id !== null) {
                                            var isBomCat = false;
                                            if(bomItem.item.parent_id !== lastParentProductId) {
                                                // Parent Product Heading
                                                categoryHeading += `
                              <tr data-parent-product-id="${bomItem.item.parent_id}" class="rowHeading">
                                <td class="fs-6 text-white bg-secondary">
                                  <input type="hidden" class="form-control" name="purchase_order_id[]" value="-1">
                                  <input type="hidden" class="form-control" name="item_id[]" value="${bomItem.item.parent_id}">
                                  <b>${bomItem.item.parent_product.model_name}</b>
                                </td>
                                <td class="fs-6 text-white bg-secondary">
                                  <b>${bomItem.item.parent_product.product_name}</b>
                                </td>
                                <td colspan="4" class="fs-6 text-white bg-secondary">
                                </td>
                                <td class="fs-6 text-white bg-secondary">
                                  <input type="number" class="form-control qtyInput" name="quantity[]" value="${bomItem.quantity ?? bomItem.bom_quantity}" required>
                                </td>
                                <td class="fs-6 text-white bg-secondary">
                                  <input type="number" class="form-control" name="total_quantity[]" value="${bomItem.total_quantity ?? bomItem.bom_total_quantity}" required>
                                </td>
                                <td colspan="27" class="bg-secondary">
                                  <div class="d-none">

                                    <input type="text" name="length[]">
                                    <select name="length_unit[]">
                                    <option></option>
                                    </select>

                                    <input type="text" name="width[]">
                                    <select name="width_unit[]">
                                    <option></option>
                                    </select>

                                    <input type="text" name="thick[]">
                                    <select name="thick_unit[]">
                                    <option></option>
                                    </select>

                                    <input type="text" name="order_length[]">
                                    <select name="order_length_unit[]">
                                    <option></option>
                                    </select>

                                    <input type="text" name="order_width[]">
                                    <select name="order_width_unit[]">
                                    <option></option>
                                    </select>

                                    <input type="text" name="bom_order_quantity[]">

                                    <input type="text" name="order_thick[]">
                                    <select name="order_thick_unit[]">
                                    <option></option>
                                    </select>

                                    <input type="hidden" name="location_receiving[]">
                                    <input type="hidden" name="location_produce[]">
                                    <input type="hidden" name="location_loading[]">

                                    <select name="stock_card_id[]">
                                    <option></option>
                                    </select>

                                    <input type="text" name="stock_card_balance_quantity[]">
                                    <input type="text" name="order_date[]">
                                    <input type="text" name="po_no[]">
                                    <input type="text" name="order_quantity[]">
                                    <input type="text" name="follow_qty_order_by_bom[]" value="0">
                                    <input type="text" name="item_price_per_unit[]">
                                    <input type="text" name="supplier_name[]">
                                    <input type="text" name="est_delievery_date[]">
                                    <input type="text" name="purchase_remarks[]">

                                    <input type="hidden" name="send_to_subcon[]" value="0">

                                    <input type="text" name="subcon_date_out[]" value="">
                                    <input type="text" name="subcon_do_no[]">
                                    <input type="text" name="subcon_quantity[]">
                                    <input type="text" name="subcon_item_price_per_unit[]">
                                    <input type="text" name="subcon_name[]">
                                    <input type="text" name="subcon_est_delievery_date[]">
                                    <input type="text" name="subcon_description[]">
                                    <input type="text" name="subcon_remarks[]">

                                  </div>
                                </td>
                              </tr>`;
                                            }
                                            lastParentProductId = bomItem.item.parent_id;
                                        }

                                        if(bomItem.item.bomcategory_id === null && bomItem.item.parent_product === null) {
                                            return;
                                        }

                                        var currentLengthUnitHtml = '';
                                        var currentWidthUnitHtml = '';
                                        var currentThickUnitHtml = '';

                                        var currentLengthUnitHtml_ = '';
                                        var currentWidthUnitHtml_ = '';
                                        var currentThickUnitHtml_ = '';

                                        $.each(unitsArr, function( index, unit ) {

                                            if(bomItem.length_unit == unit) {
                                                currentLengthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
                                            } else {
                                                currentLengthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
                                            }

                                            if(bomItem.item.length_unit == unit) {
                                                currentLengthUnitHtml_ += '<option value=' + unit + ' selected>' + unit + '</option>';
                                            } else {
                                                currentLengthUnitHtml_ += '<option value=' + unit + '>' + unit + '</option>';
                                            }

                                            if(bomItem.width_unit == unit) {
                                                currentWidthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
                                            } else {
                                                currentWidthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
                                            }

                                            if(bomItem.thick_unit == unit) {
                                                currentThickUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
                                            } else {
                                                currentThickUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
                                            }

                                            if(bomItem.item.width_unit == unit) {
                                                currentWidthUnitHtml_ += '<option value=' + unit + ' selected>' + unit + '</option>';
                                            } else {
                                                currentWidthUnitHtml_ += '<option value=' + unit + '>' + unit + '</option>';
                                            }

                                            if(bomItem.item.thick_unit == unit) {
                                                currentThickUnitHtml_ += '<option value=' + unit + ' selected>' + unit + '</option>';
                                            } else {
                                                currentThickUnitHtml_ += '<option value=' + unit + '>' + unit + '</option>';
                                            }

                                        });

                                        var currentSupplierHtml = '';
                                        $.each(supplierArr, function( index, supplier ) {
                                            if(bomItem.supplier_name == supplier) {
                                                currentSupplierHtml += '<option value="' + supplier + '" selected>' + supplier + '</option>';
                                            } else {
                                                currentSupplierHtml += '<option value="' + supplier + '">' + supplier + '</option>';
                                            }
                                        });

                                        bomListHtmlData += `
                          <tr data-bom-item-id="${bomItem.item_id}">
                            <td>
                              <input type="hidden" class="form-control" name="purchase_order_id[]" value="${bomItem.id}">
                              <input type="hidden" class="form-control" name="item_id[]" value="${bomItem.item_id}">
                              ${bomItem.item.model_name ?? "N/A"}
                            </td>
                            <td>${bomItem.item.product_name ?? "N/A"}</td>
                            <td>${bomItem.item.material ?? "N/A"}</td>

                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="length[]" value="${bomItem.length ?? ''}">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" id="length_unit" name="length_unit[]">
                                    <option></option>
                                    ${currentLengthUnitHtml}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="width[]" value="${bomItem.width ?? ''}">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" id="width_unit" name="width_unit[]">
                                    <option></option>
                                    ${currentWidthUnitHtml}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm"  name="thick[]" value="${bomItem.thick ?? ''}">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
                                    <option></option>
                                    ${currentThickUnitHtml}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <input type="number" class="form-control qtyInput"  name="quantity[]" value="${bomItem.quantity ?? bomItem.bom_quantity}" required>
                            </td>
                            <td>
                              <input type="number" class="form-control dontAffectChange" name="total_quantity[]" value="${bomItem.total_quantity ?? bomItem.bom_total_quantity}" required>
                            </td>
                    <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="order_length_[]" value="${bomItem.item.length ?? ''}">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" id="length_unit_" name="order_length_unit_[]">
                                    <option></option>
                                    ${currentLengthUnitHtml_}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="order_width_[]" value="${bomItem.item.width ?? ''}">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" id="width_unit_" name="order_width_unit_[]">
                                    <option></option>
                                    ${currentWidthUnitHtml_}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="order_thick_[]" value="${bomItem.item.thick ?? ''}">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" id="thick_unit_" name="order_thick_unit_[]">
                                    <option></option>
                                    ${currentThickUnitHtml_}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <input type="number" class="form-control orderQtyByBomInput_" name="bom_order_quantity_[]" value="${bomItem.bom_quantity ?? bomItem.bom_quantity}">
                            </td>




                <td>
                              <select class="form-select form-select-sm" id="follow_qty_order_by_bom" onchange="EnableDisableFields(this)" name="follow_qty_order_by_bom[]">
                                <option value="1" ${parseInt(bomItem.follow_qty_order_by_bom) ? 'selected' : '' }>Yes</option>
                                <option value="0" ${!(parseInt(bomItem.follow_qty_order_by_bom)) ? 'selected' : '' }>No</option>
                              </select>
                            </td>

                <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="order_length[]" ${parseInt(bomItem.follow_qty_order_by_bom) ? 'readonly' : ''} value="${(parseInt(bomItem.follow_qty_order_by_bom)) ? bomItem.item.length : bomItem.order_length}">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" ${parseInt(bomItem.follow_qty_order_by_bom) ? 'disabled' : ''} id="length_unit" name="order_length_unit[]">
                                    <option></option>
                                    ${currentLengthUnitHtml}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" ${parseInt(bomItem.follow_qty_order_by_bom) ? 'readonly' : ''} name="order_width[]" value="${ (parseInt(bomItem.follow_qty_order_by_bom)) ? bomItem.item.width : bomItem.order_width }">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" ${parseInt(bomItem.follow_qty_order_by_bom) ? 'disabled' : ''} id="width_unit" name="order_width_unit[]">
                                    <option></option>
                                    ${currentWidthUnitHtml}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" ${parseInt(bomItem.follow_qty_order_by_bom) ? 'readonly' : ''} name="order_thick[]" value="${ (parseInt(bomItem.follow_qty_order_by_bom)) ? bomItem.item.thick : bomItem.order_thick }">
                                <span class="input-group-text">
                                  <select class="form-select form-select-sm" ${parseInt(bomItem.follow_qty_order_by_bom) ? 'disabled' : ''} id="thick_unit" name="order_thick_unit[]">
                                    <option></option>
                                    ${currentThickUnitHtml}
                                  </select>
                                </span>
                              </div>
                            </td>
                            <td>
                              <input type="number" class="form-control orderQtyByBomInput" name="bom_order_quantity[]" value="${bomItem.bom_order_quantity ?? bomItem.order_quantity}">
                            </td>

                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="location_receiving[]"
                                  value="${bomItem.location_receiving ?? ''}">
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="location_produce[]"
                                  value="${bomItem.location_produce ?? ''}">
                              </div>
                            </td>
                            <td>
                              <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm" name="location_loading[]"
                                  value="${bomItem.location_loading ?? ''}">
                              </div>
                            </td>

                            <td>
                              <select class="form-select form-select-sm" name="stock_card_id[]" readonly style="pointer-events: none;">
                                <option value="${bomItem.stock_card_id ?? ''}">${bomItem?.stock_card?.stock_card_number ?? ''}</option>
                              </select>
                              <a href="javascript:void(0);" data-bom-item-id="${bomItem.item_id}" class="stockCardSelector"><small>Select Stock Card</small></a>
                              <a href="javascript:void(0);" class="removeStockCardSelector text-danger"><small>Remove Card</small></a>
                            </td>
                            <td>
                              <input type="text" class="form-control" name="stock_card_balance_quantity[]" value="${bomItem?.stock_card?.available_quantity ?? ''}" readonly>
                            </td>

                            <td>
                              <input type="text" class="form-control datepicker date_order_inputs" name="order_date[]" value="${bomItem.order_date ?? ''}">
                            </td>
                            <td>
                              <input type="text" class="form-control po_no_inputs" name="po_no[]" value="${bomItem.po_no ?? ''}">
                            </td>
                            <td>
                              <input type="text" class="form-control qtyNeedToOrder" name="order_quantity[]" value="${bomItem.order_quantity ?? ''}">
                            </td>
                            <td>
                              <input type="text" class="form-control" name="item_price_per_unit[]" value="${bomItem.item_price_per_unit ?? ''}">
                            </td>
                            <td>
                              <select name="supplier_name[]" class="form-select form-select-sm supplier_selects">
                                <option value=""></option>
                                ${currentSupplierHtml}
                              </select>
                            </td>
                            <td>
                              <input type="text" class="form-control datepicker delievery_dates" name="est_delievery_date[]" value="${bomItem.est_delievery_date ?? ''}">
                            </td>
                            <td>
                              <input type="text" class="form-control" name="purchase_remarks[]" value="${bomItem.purchase_remarks ?? ''}">
                            </td>
                            <td class="text-center">
                              <input type="hidden" name="send_to_subcon[]" value="0" ${bomItem.send_to_subcon == 1 ? 'disabled' : ''}>
                              <input class="form-check-input sendToSubconCheckBox" type="checkbox" value="1" ${bomItem.send_to_subcon == 1 ? 'checked' : ''} name="send_to_subcon[]">
                            </td>
                            <td>
                              <input type="text" class="form-control datepicker" name="subcon_date_out[]" value="${bomItem.subcon_date_out ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>
                            <td>
                              <input type="text" class="form-control" name="subcon_do_no[]" value="${bomItem.subcon_do_no ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>
                            <td>
                              <input type="text" class="form-control" name="subcon_quantity[]" value="${bomItem.subcon_quantity ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>
                            <td>
                              <input type="text" class="form-control" name="subcon_item_price_per_unit[]" value="${bomItem.subcon_item_price_per_unit ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>
                            <td>
                              <input type="text" class="form-control" name="subcon_name[]" value="${bomItem.subcon_name ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>
                            <td>
                              <input type="text" class="form-control datepicker" name="subcon_est_delievery_date[]" value="${bomItem.subcon_est_delievery_date ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>
                            <td>
                              <input type="text" class="form-control" name="subcon_description[]" value="${bomItem.subcon_description ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>
                            <td>
                              <input type="text" class="form-control" name="subcon_remarks[]" value="${bomItem.subcon_remarks ?? ''}" ${bomItem.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
                            </td>

                            <td>
                              <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${bomItem.item_id}"><i
                                  class="far fa-eye"></i></a>
                              <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
                            </td>
                          </tr>
                          `;


                        if(isBomCat) {
                            var isHeadingExists = checkIfCategoryExists(bomItem.item.bomcategory_id);
                        } else {
                            var isHeadingExists = checkIfParentProductExists(bomItem.item.parent_id);
                        }

                        // Add new Heading Row
                        var htmlBody = '';
                        if(isHeadingExists === false) {
                            htmlBody += categoryHeading;
                            htmlBody += bomListHtmlData;
                            $('.BOMProductsList tbody').append(htmlBody);
                        }

                        // Add new product under exisiting Heading
                        if(isHeadingExists !== false) {
                            htmlBody += bomListHtmlData;
                            $(".BOMProductsList tbody tr:nth-child(" + (isHeadingExists+1) + ")").after(htmlBody);
                        }

                    });

                    // Update Date Pickers
                    initDatePicker();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
                }
            });

        });

        function EnableDisableFields(element){
            if(parseInt($(element).val())) {
                $(element).parent().next().find('input').val($(element).parent().prev().prev().prev().prev().find('input').val());
                $(element).parent().next().next().find('input').val($(element).parent().prev().prev().prev().find('input').val());
                $(element).parent().next().next().next().find('input').val($(element).parent().prev().prev().find('input').val());
                $(element).parent().next().next().next().next().find('input').val($(element).parent().prev().find('input').val());



                $(element).parent().next().find('select').val($(element).parent().prev().prev().prev().prev().find('select').val());
                $(element).parent().next().next().find('select').val($(element).parent().prev().prev().prev().find('select').val());
                $(element).parent().next().next().next().find('select').val($(element).parent().prev().prev().find('select').val());
                $(element).parent().next().next().next().next().find('select').val($(element).parent().prev().find('select').val());


                $(element).parent().next().find('input').attr('readonly', true);
                $(element).parent().next().next().find('input').attr('readonly', true);
                $(element).parent().next().next().next().find('input').attr('readonly', true);
                $(element).parent().next().find('select').attr('disabled', true);
                $(element).parent().next().next().find('select').attr('disabled', true);
                $(element).parent().next().next().next().find('select').attr('disabled', true);
            }else{
                $(element).parent().next().find('input').attr('readonly', false);
                $(element).parent().next().next().find('input').attr('readonly', false);
                $(element).parent().next().next().next().find('input').attr('readonly', false);
                $(element).parent().next().find('select').attr('disabled', false);
                $(element).parent().next().next().find('select').attr('disabled', false);
                $(element).parent().next().next().next().find('select').attr('disabled', false);
            }
        }

        $(document).on('click', '.viewProductBtn', function() {
            var productId = $(this).attr('data-id');
            var routeUrl = "{{ route('products.show', ':id')}}";
            routeUrl = routeUrl.replace(':id', productId);
            window.open(routeUrl, '_blank');
        });

        $(document).on('click', '.removeProductBtn', function() {
            var row = $(this).closest('tr');
            var isPrevRowHeading = row.prev('tr').hasClass('rowHeading');
            var prevRow = row.prev('tr');
            var nextRow = row.next('tr');
            var isNextRowHeading = row.next('tr').hasClass('rowHeading');

            if(confirm('Are you sure?')) {
                //Remove Product
                $(row).remove();

                // Remove BOM Category if no product left
                if(isPrevRowHeading && (nextRow.length == 0 || isNextRowHeading)) {
                    prevRow.remove();
                }
            }
        });

        // Add To BOM List
        function addToBom(p='') {
  
          if(p=='')
          {
            var productId = $(".ajax-products").val();

            if(productId == '' || productId.length == 0) {
                alert('Select A product First');
                return;
            }

            var routeUrl = "{{ route('products.show', 'show1/:id') }}"+"/"+$(".ajax-job-orders").val();
            routeUrl = routeUrl.replace(':id', productId);

            $.get( routeUrl, function(data) {
                populateInsidePurchaseList(data);
            })
                .fail(function() {
                    console.log( "error" );
                });
          }
          else{
            $(p).each(function(index,productId){
              
              var routeUrl = window.location.origin+"/public/products/show1/"+productId+"/"+$(".ajax-job-orders").val();

            $.get( routeUrl, function(data) {
                populateInsidePurchaseList(data);
            })
                .fail(function() {
                    console.log( "error" );
                });

            })

            
          }
        }

        function populateInsidePurchaseList(productData) {
            productData=productData[0];

            var selectedJobOrder = $(".ajax-job-orders").val();
            var selectedProduct = $(".job-orders-products").val();

            // Find Job Order Product Details
            $.each(currentJobOrderProducts, function( index, productOrder ) {
                if( productOrder.product_id == selectedProduct && productOrder.order_id == selectedJobOrder ) {
                    jobOrderProductDetails = productOrder;
                    return false;
                }
            });
            /*

            if(!productData.sub_category.name) {
                 alert('invalid product, dont have sub category name');
                 return;
             }
            */
            // var subCategoryCode = productData?.sub_category?.name ?? '';
            // if(subCategoryCode != '') {
            //     subCategoryCode = (subCategoryCode).charAt(0);
            // }
            // var productCodeForBom = subCategoryCode;
            var currentLengthUnitHtml = '';
            var currentWidthUnitHtml = '';
            var currentThickUnitHtml = '';

            $.each(unitsArr, function( index, unit ) {

                if(productData.length_unit == unit) {
                    currentLengthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
                } else {
                    currentLengthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
                }

                if(productData.width_unit == unit) {
                    currentWidthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
                } else {
                    currentWidthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
                }

                if(productData.thick_unit == unit) {
                    currentThickUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
                } else {
                    currentThickUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
                }

            });

            var currentSupplierHtml = '';
            $.each(supplierArr, function( index, supplier ) {
                if(jobOrderProductDetails.supplier_name == supplier) {
                    currentSupplierHtml += '<option value="' + supplier + '" selected>' + supplier + '</option>';
                } else {
                    currentSupplierHtml += '<option value="' + supplier + '">' + supplier + '</option>';
                }
            });

            var isBomCat = true;
            if(productData.has_bom_items !== 0) {
                // BOM Category Heading
                var categoryHeading = `
      <tr data-bom-cat-id="${productData.category_id}" class="rowHeading">
        <td colspan="36" class="fs-5 text-white text-center bg-info">
          <b>${productData.name}</b>
        </td>
      </tr>`;
            } else {
                isBomCat = false;
                // Parent Product Heading
                var categoryHeading = `
      <tr data-parent-product-id="${productData.parent_id}" class="rowHeading">
        <td class="fs-6 text-white bg-secondary">
          <input type="hidden" class="form-control" name="purchase_order_id[]" value="0">
          <input type="hidden" class="form-control" name="item_id[]" value="${productData.parent_id}">
          <b>${productData.parent_product_model_name}</b>
        </td>
        <td class="fs-6 text-white bg-secondary">
          <b>${productData.parent_product_product_name}</b>
        </td>
        <td colspan="4" class="fs-6 text-white bg-secondary"></td>
        <td class="fs-6 text-white bg-secondary">
          <input type="number" class="form-control qtyInput" name="quantity[]" value="1" required>
        </td>
        <td class="fs-6 text-white bg-secondary">
          <input type="number" class="form-control" name="total_quantity[]" value="${1 * jobOrderProductDetails.quantity}" required>
        </td>
        <td colspan="27" class="bg-secondary">
          <div class="d-none">
            <input type="text" class="form-control form-control-sm" name="length[]">
            <select class="form-select form-select-sm" id="length_unit" name="length_unit[]">
              <option></option>
            </select>

            <input type="text" class="form-control form-control-sm" name="width[]">
            <select class="form-select form-select-sm" id="width_unit" name="width_unit[]">
              <option></option>
            </select>

            <input type="text" class="form-control form-control-sm" name="thick[]">
            <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
              <option></option>
            </select>

            <input type="number" class="form-control qtyInput" name="quantity[]" value="0">
            <input type="number" class="form-control" name="total_quantity[]" value="0">
            <input type="number" class="form-control" name="follow_qty_order_by_bom[]" value="0">

            <input type="text" class="form-control form-control-sm" name="order_length[]">
            <select class="form-select form-select-sm" id="order_length_unit" name="order_length_unit[]">
              <option></option>
            </select>

            <input type="text" class="form-control form-control-sm" name="order_width[]">
            <select class="form-select form-select-sm" id="order_width_unit" name="order_width_unit[]">
              <option></option>
            </select>

            <input type="text" class="form-control form-control-sm" name="bom_order_quantity[]">

            <input type="text" class="form-control form-control-sm" name="order_thick[]">
            <select class="form-select form-select-sm" id="order_thick_unit" name="order_thick_unit[]">
              <option></option>
            </select>

            <select class="form-select form-select-sm" name="stock_card_id[]">
              <option></option>
            </select>

            <input type="hidden" name="location_receiving[]">
            <input type="hidden" name="location_produce[]">
            <input type="hidden" name="location_loading[]">

            <input type="text" class="form-control" name="stock_card_balance_quantity[]">
            <input type="text" class="form-control datepicker" name="order_date[]">
            <input type="text" class="form-control" name="po_no[]">
            <input type="text" class="form-control" name="order_quantity[]">
            <input type="text" class="form-control" name="item_price_per_unit[]">
            <select name="supplier_name[]" class="form-select form-select-sm">
              <option value=""></option>
            </select>
            <input type="text" class="form-control datepicker delievery_dates" name="est_delievery_date[]">
            <input type="text" class="form-control" name="purchase_remarks[]">
            <input type="hidden" name="send_to_subcon[]" value="0">
            <input class="form-check-input sendToSubconCheckBox" type="checkbox" value="1" name="send_to_subcon[]">
            <input type="text" class="form-control datepicker" name="subcon_date_out[]">
            <input type="text" class="form-control" name="subcon_do_no[]">
            <input type="text" class="form-control" name="subcon_quantity[]">
            <input type="text" class="form-control" name="subcon_item_price_per_unit[]">
            <input type="text" class="form-control" name="subcon_name[]">
            <input type="text" class="form-control datepicker" name="subcon_est_delievery_date[]">
            <input type="text" class="form-control" name="subcon_description[]">
            <input type="text" class="form-control" name="subcon_remarks[]">
          </div>
        </td>
      </tr>`;
            }

            var itemData = `
      <tr data-bom-item-id="${productData.id}">
        <td>
          <input type="hidden" class="form-control" name="purchase_order_id[]" value="0">
          <input type="hidden" class="form-control" name="item_id[]" value="${productData.id}">
          ${productData.model_name ?? "N/A" }
        </td>
        <td>${productData.product_name ?? "N/A" }</td>
        <td>${productData.material ?? "N/A" }</td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="length[]" value="${productData.length ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="length_unit" name="length_unit[]">
                <option></option>
                ${currentLengthUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="width[]" value="${productData.width ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="width_unit" name="width_unit[]">
                <option></option>
                ${currentWidthUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="thick[]" value="${productData.thick ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
                <option></option>
                ${currentThickUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <input type="number" class="form-control qtyInput" name="quantity[]" value="1" required>
        </td>
        <td>
          <input type="number" class="form-control" name="total_quantity[]" value="${1 * jobOrderProductDetails.quantity}" readonly required>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="order_length[]" value="${productData.order_length ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="order_length_unit" name="order_length_unit[]">
                <option></option>
                ${currentLengthUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="order_width[]" value="${productData.order_width ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="order_width_unit" name="order_width_unit[]">
                <option></option>
                ${currentWidthUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="order_thick[]" value="${productData.order_thick ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="order_thick_unit" name="order_thick_unit[]">
                <option></option>
                ${currentThickUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <input type="number" class="form-control qtyInput orderQtyByBomInput" name="bom_order_quantity[]" value="1">
        </td>
        <td>
          <select class="form-select form-select-sm" id="follow_qty_order_by_bom" name="follow_qty_order_by_bom[]">
            <option value="1" ${jobOrderProductDetails.follow_qty_order_by_bom ? 'selected' : '' }>Yes</option>
            <option value="0" ${jobOrderProductDetails.follow_qty_order_by_bom ? 'selected' : '' }>No</option>
          </select>
        </td>    
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="order_length[]" value="${productData.order_length ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="order_length_unit" name="order_length_unit[]">
                <option></option>
                ${currentLengthUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="order_width[]" value="${productData.order_width ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="order_width_unit" name="order_width_unit[]">
                <option></option>
                ${currentWidthUnitHtml}
              </select>
            </span>
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="order_thick[]" value="${productData.order_thick ?? ''}">
            <span class="input-group-text">
              <select class="form-select form-select-sm" id="order_thick_unit" name="order_thick_unit[]">
                <option></option>
                ${currentThickUnitHtml}
              </select>
            </span>
          </div>
        </td>
        
        
        <td>
          <input type="number" class="form-control qtyInput orderQtyByBomInput" name="bom_order_quantity[]" value="1">
        </td>
        
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="location_receiving[]"
              value="${jobOrderProductDetails.location_receiving ?? ''}">
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="location_produce[]"
              value="${jobOrderProductDetails.location_produce ?? ''}">
          </div>
        </td>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="location_loading[]"
              value="${jobOrderProductDetails.location_loading ?? ''}">
          </div>
        </td>
        <td>
          <select class="form-select form-select-sm" name="stock_card_id[]" readonly style="pointer-events: none;">
            <option></option>
          </select>
          <a href="javascript:void(0);" data-bom-item-id="${productData.item_id}" class="stockCardSelector"><small>Select Stock
              Card</small></a>
          <a href="javascript:void(0);" class="removeStockCardSelector text-danger"><small>Remove Card</small></a>
        </td>
        <td>
          <input type="text" class="form-control" name="stock_card_balance_quantity[]" value="${jobOrderProductDetails.stock_card_balance_quantity ?? ''}" readonly>
        </td>

        <td>
          <input type="text" class="form-control datepicker date_order_inputs" name="order_date[]" value="${jobOrderProductDetails.order_date ?? ''}">
        </td>
        <td>
          <input type="text" class="form-control po_no_inputs" name="po_no[]" value="${jobOrderProductDetails.po_no ?? ''}">
        </td>
        <td>
          <input type="text" class="form-control qtyNeedToOrder" name="order_quantity[]" value="${jobOrderProductDetails.order_quantity ?? ''}">
        </td>
        <td>
          <input type="text" class="form-control" name="item_price_per_unit[]" value="${jobOrderProductDetails.item_price_per_unit ?? ''}">
        </td>
        <td>
          <select name="supplier_name[]" class="form-select form-select-sm supplier_selects">
            <option value=""></option>
            ${currentSupplierHtml}
          </select>
        </td>
        <td>
          <input type="text" class="form-control datepicker delievery_dates" name="est_delievery_date[]" value="${jobOrderProductDetails.est_delievery_date ?? ''}">
        </td>
        <td>
          <input type="text" class="form-control" name="purchase_remarks[]" value="${jobOrderProductDetails.purchase_remarks ?? ''}">
        </td>
        <td class="text-center">
          <input type="hidden" name="send_to_subcon[]" value="0" ${jobOrderProductDetails.send_to_subcon == 1 ? 'disabled' : ''}>
          <input class="form-check-input sendToSubconCheckBox" type="checkbox" value="1" ${jobOrderProductDetails.send_to_subcon == 1 ? 'checked' : ''} name="send_to_subcon[]">
        </td>
        <td>
          <input type="text" class="form-control datepicker" name="subcon_date_out[]" value="${jobOrderProductDetails.subcon_date_out ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>
        <td>
          <input type="text" class="form-control" name="subcon_do_no[]" value="${jobOrderProductDetails.subcon_do_no ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>
        <td>
          <input type="text" class="form-control" name="subcon_quantity[]" value="${jobOrderProductDetails.subcon_quantity ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>
        <td>
          <input type="text" class="form-control" name="subcon_item_price_per_unit[]" value="${jobOrderProductDetails.subcon_item_price_per_unit ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>
        <td>
          <input type="text" class="form-control" name="subcon_name[]" value="${jobOrderProductDetails.subcon_name ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>
        <td>
          <input type="text" class="form-control datepicker" name="subcon_est_delievery_date[]" value="${jobOrderProductDetails.subcon_est_delievery_date ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>
        <td>
          <input type="text" class="form-control" name="subcon_description[]" value="${jobOrderProductDetails.subcon_description ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>
        <td>
          <input type="text" class="form-control" name="subcon_remarks[]" value="${jobOrderProductDetails.subcon_remarks ?? ''}" ${jobOrderProductDetails.send_to_subcon == 1 ? '' : 'readonly style="pointer-events: none;"'}>
        </td>

        <td>
          <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${productData.id}"><i class="far fa-eye"></i></a>

          <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
        </td>
      </tr>
      `;

            if(productData.has_bom_items) {
                var isHeadingExists = checkIfCategoryExists(productData.category_id);
            } else {
                var isHeadingExists = checkIfParentProductExists(productData.parent_id);
            }

            var htmlBody = '';

            // Add new Heading Row
            if(isHeadingExists === false) {
                htmlBody += categoryHeading;
                htmlBody += itemData;
                $('.BOMProductsList tbody').append(htmlBody);
            }

            // Add new product under exisiting Heading
            if(isHeadingExists !== false) {
                htmlBody += itemData;
                $(".BOMProductsList tbody tr:nth-child(" + (isHeadingExists+1) + ")").after(htmlBody);
            }

            combine_models();

            // Reset Select2 Input
            $(".ajax-products").val('').trigger('change');

            // Update Date Pickers
            initDatePicker();
        }

        // Check if BOM category exists in table
        function checkIfCategoryExists(BOMCatId) {
            var found = false;
            $('.BOMProductsList tbody > tr').each(function(index, tr) {
                var currentId = $(tr).attr('data-bom-cat-id');
                if(currentId == BOMCatId) {
                    found = index;
                }
            });
            return found;
        }

        // Check if Parent Product exists in table
        function checkIfParentProductExists(parentProductId) {
            var found = false;
            $('.BOMProductsList tbody > tr').each(function(index, tr) {
                var currentId = $(tr).attr('data-parent-product-id');
                if(currentId == parentProductId) {
                    found = index;
                }
            });
            return found;
        }

        $(document).ready(function(){

          $('#multi-product-table thead td').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
 
    // DataTable
    var table = $('#multi-product-table').DataTable({
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.header()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
        },


    });

            $('body').on("change",'.qtyInput', function() {
                var selectedJobOrder = $(".ajax-job-orders").val();
                var selectedProduct = $(".job-orders-products").val();
                var selectedProductQty = $('.job-orders-products').find(":selected").attr('data-qty');

                if(combineQtyForCalculation > 0) {
                    var newTotal = $(this).val() * parseInt(combineQtyForCalculation);
                } else {
                    var newTotal = $(this).val() * parseInt(selectedProductQty);
                }
                $(this).parent().next().find('input').val(newTotal);
            });

            $('body').on("keyup",'.qtyInput', function() {
                var selectedJobOrder = $(".ajax-job-orders").val();
                var selectedProduct = $(".job-orders-products").val();
                var selectedProductQty = $('.job-orders-products').find(":selected").attr('data-qty');

                if(combineQtyForCalculation > 0) {
                    var newTotal = $(this).val() * parseInt(combineQtyForCalculation);
                } else {
                    var newTotal = $(this).val() * parseInt(selectedProductQty);
                }
                $(this).parent().next().find('input').val(newTotal);
            });

            $('body').on("click",'.sendToSubconCheckBox', function() {
                var currentIndex = $(this).parent().index() + 1;
                var lastIndex = currentIndex + 8;

                if ($(this).is(':checked')) {
                    for (let i = currentIndex; i < lastIndex; i++) {
                        var nextTd = $(this).parent().parent().find( "td:eq("+ i + ")" );
                        nextTd.find('input').removeAttr('style');
                        nextTd.find('input').prop('readonly', false);
                    }
                    $(this).siblings("input[type=hidden]").prop('disabled', true);
                    $(this).siblings("input[type=hidden]").attr('disabled', true);
                } else {
                    for (let i = currentIndex; i < lastIndex; i++) {
                        var nextTd = $(this).parent().parent().find( "td:eq("+ i + ")" );
                        nextTd.find('input').attr('style', 'pointer-events: none;');
                        nextTd.find('input').prop('readonly', true);
                    }
                    $(this).siblings("input[type=hidden]").val();
                    $(this).siblings("input[type=hidden]").prop('disabled', false);
                    $(this).siblings("input[type=hidden]").attr('disabled', false);
                }
            });

            $('body').on("click",'.stockCardSelector', function() {
                var itemId = $(this).attr('data-bom-item-id');
                var routeUrl = "{{ route('product.stock.ajax', ':id') }}";
                routeUrl = routeUrl.replace(':id', itemId);

                $.ajax({
                    url: routeUrl,
                    type: "get",
                    success: function (response) {
                        var stockTbodyHtml = '';
                        $.each(response, function( index, stockCard ) {
                            stockTbodyHtml += `
              <tr>
                <td>${stockCard.id}</td>
                <td>${stockCard.order_id}</td>
                <td>${stockCard.stock_card_number}</td>
                <td>${stockCard.available_quantity}</td>
                <td><a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="useStockCardInPurchase('${stockCard.id}', '${stockCard.stock_card_number}', '${stockCard.available_quantity}', '${itemId}' )">Use This Stock Card</a></td>
              </tr>
              `;
                        });

                        if(response.length === 0) {
                            stockTbodyHtml += `
              <tr>
                <td colspan="4" class="text-center">No Stock Available!</td>
              </tr>
              `;
                        }

                        $(".stockCardsListTable table tbody").html(stockTbodyHtml);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
                    }
                });

                $('#stockCardSelectorModal').modal('show');
            });


            $('body').on("click",'.removeStockCardSelector', function() {
                $(this).siblings('select').html(`<option></option>`);
                $(this).parent().next().find('input').val('');
            });

            initDatePicker();
        });

        @if(!empty($jobOrder))
        $('.ajax-job-orders').trigger("change");
        @endif

        function useStockCardInPurchase(id, stock_card_number, available_quantity, itemId) {
            var trEle = $("tr").find(`[data-bom-item-id='${itemId}']`);
            trEle.siblings('select').html(`<option value="${id}" selected>${stock_card_number}</option>`);
            trEle.parent().next().find('input').val(available_quantity);

            $('#stockCardSelectorModal').modal('hide');
        }

        $('.same_est_delievery_checkbox').click(function() {
            var isChecked = $(this).is(':checked');

            if(isChecked) {
                var dateVal = $('.same_est_delievery_input').val();
                var dateInputs = $('.delievery_dates');

                $( dateInputs ).each(function( index, input ) {
                    $( input ).val(dateVal);
                });
            }
        });

        $('.same_est_delievery_input').change(function() {
            var isChecked = $('.same_est_delievery_checkbox').is(':checked');

            if(isChecked) {
                var dateVal = $('.same_est_delievery_input').val();
                var dateInputs = $('.delievery_dates');

                $( dateInputs ).each(function( index, input ) {
                    $( input ).val(dateVal);
                });
            }
        });

        $('.same_po_no_checkbox').click(function() {
            var isChecked = $(this).is(':checked');

            if(isChecked) {
                var inputVal = $('.same_po_no_input').val();
                var inputs = $('.po_no_inputs');

                $( inputs ).each(function( index, input ) {
                    $( input ).val(inputVal);
                });
            }
        });

        $('.same_po_no_input').change(function() {
            var isChecked = $('.same_po_no_checkbox').is(':checked');

            if(isChecked) {
                var inputVal = $('.same_po_no_input').val();
                var inputs = $('.po_no_inputs');

                $( inputs ).each(function( index, input ) {
                    $( input ).val(inputVal);
                });
            }
        });

        $('.same_supplier_checkbox').click(function() {
            var isChecked = $(this).is(':checked');

            if(isChecked) {
                var selectVal = $('.same_supplier_input').val();
                var selects = $('.supplier_selects');

                $( selects ).each(function( index, select ) {
                    $( select ).val(selectVal);
                });
            }
        });

        $('.same_supplier_input').change(function() {
            var isChecked = $('.same_supplier_checkbox').is(':checked');

            if(isChecked) {
                var selectVal = $('.same_supplier_input').val();
                var selects = $('.supplier_selects');

                $( selects ).each(function( index, select ) {
                    $( select ).val(selectVal);
                });
            }
        });

        $('.same_date_order_checkbox').click(function() {
            var isChecked = $(this).is(':checked');

            if(isChecked) {
                var dateVal = $('.same_date_order_input').val();
                var dateInputs = $('.date_order_inputs');

                $( dateInputs ).each(function( index, input ) {
                    $( input ).val(dateVal);
                });
            }
        });

        $('.same_date_order_input').change(function() {
            var isChecked = $('.same_date_order_checkbox').is(':checked');

            if(isChecked) {
                var dateVal = $('.same_date_order_input').val();
                var dateInputs = $('.date_order_inputs');

                $( dateInputs ).each(function( index, input ) {
                    $( input ).val(dateVal);
                });
            }
        });

        $('.combine_models_bom_checkbox').click(function() {
            combine_models();
        });

        function combine_models() {
            var isChecked = $('.combine_models_bom_checkbox').is(':checked');
            var selectedModel = $('.job-orders-products').find(":selected").attr('data-model');
            var selectedModelId = $('.job-orders-products').find(":selected").val();

            if(isChecked) {
                $('.combine_models_bom_hidden').val('1');
                var totalQty = 0;
                $.each(jobProductsList, function( index, jobProduct ) {
                    if(jobProduct.product.model_name == selectedModel) {
                        totalQty += jobProduct.quantity;
                    }
                });
                combineQtyForCalculation = totalQty;
                combineAllQuantities(totalQty);
            } else {
                $('.combine_models_bom_hidden').val('0');
                $.each(jobProductsList, function( index, jobProduct ) {
                    if(jobProduct.product.id == selectedModelId) {
                        combineQtyForCalculation = jobProduct.quantity;
                        combineAllQuantities(jobProduct.quantity);
                        return false;
                    }
                });
            }
        }

        function combineAllQuantities(totalQty) {
            var inputsOfTotalQty = $("input[name='total_quantity[]']");
            var inputsOfQty = $("input[name='quantity[]']");
            $.each(inputsOfTotalQty, function( index, inputQty ) {
                var qty = parseInt($(inputsOfQty[index]).val());
                $(inputQty).val(totalQty * qty);
            });
            $('.productDetails ul li:last-child').html('<b>Job Order Quantity</b>: ' + totalQty);
        }

        $(document).ready(function() {
            setTimeout(() => {
                var isCheckedCombineModels = $('.combine_models_bom_checkbox').is(':checked');
                if(isCheckedCombineModels) {
                    combine_models();
                }
            }, 700);
        });

        $('body').on("change",'#follow_qty_order_by_bom', function() {
            var followQtyOrderByBOM = $(this).val();
            var orderQty = $(this).parent().parent().find('.orderQtyByBomInput').val();
            if(followQtyOrderByBOM == 1) {
                $(this).parent().parent().find('.qtyNeedToOrder').val(orderQty);
            } else {
                $(this).parent().parent().find('.qtyNeedToOrder').val('0');
            }
        });

        $('body').on("change",'.orderQtyByBomInput', function() {
            var followQtyOrderByBOM = $(this).parent().parent().find('#follow_qty_order_by_bom').val();
            var orderQty = $(this).val();
            if(followQtyOrderByBOM == 1) {
                $(this).parent().parent().find('.qtyNeedToOrder').val(orderQty);
            }
        });

        $("#AddItemsInTable").on('click',function (){
      var selected = [];
      $('#multi-product-table tbody input[type=checkbox]:checked').each(function(a,b){
          selected.push($(this).parents("tr").find('#id_prod').html());
      });
        addToBom(selected);
        $("#multi-product-table input:checked").prop("checked", false);

        });
    </script>
  
  <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap5.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.0/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.0/datatables.min.js"></script>
@endsection



