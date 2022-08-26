@extends('layouts.app')

@section('custom_css')
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">
<style>
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
</style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-luggage-cart"></i> Manage BOM List</h1>
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
                  <h4 class="card-title">Update BOM List
                  <a href="{{ route('job-order.bom.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
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
                                <option data-model="{{ $jobProducts->product->model_name }}" data-qty="{{ $jobProducts->quantity }}" value="{{ $jobProducts->product->id }}" @if($jobProducts->product->id == $productId) selected @endif>{{ $jobProducts->product->model_name }} [{{ $jobProducts->product->product_name }}] {{ $jobProducts->product->length }}x{{ $jobProducts->product->width }}x{{ $jobProducts->product->thick }}</option>
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

                        @if( Auth::user()->isPermissionAllowed('bom-list.edit') )
                        <input class="form-check-input combine_models_bom_checkbox ms-2" type="checkbox" name="combine_models_bom" value="1" {{ $isBomModelCombine==1 ? 'checked' : '' }}> Combine Model BOM
                        @endif
                      </div>

                      <div class="col-md-12 bomListBox d-none">

                        @if( Auth::user()->isPermissionAllowed('bom-list.edit') )
                        <hr>

                        <h4>Add More Items in BOM List:</h4>
                        <!-- <div class="form-group">
                          <label for="">Search Items:</label>
                          <select class="ajax-products form-select">
                            <option></option>
                          </select>
                        </div> -->

                        <div class="form-group mt-1">
                          <button data-bs-toggle="modal" data-bs-target="#addMultipleProducts" id="AddItemsInTable_" class="btn btn-sm btn-primary" style="display: none;">Add Multiple Items</button>
                        </div>
                        @endif

                        <hr>
                        <h4>BOM List:</h4>
                        <hr>

                        <form method="post" action="{{ route('job-order.bom.store') }}">
                          <input class="combine_models_bom_hidden" type="hidden" name="combine_models_bom_hidden" value="{{ $isBomModelCombine }}">
                          @csrf

                          <div class="table-responsive BOMProductsList">
                            <table class="table table-striped table-hover table-bordered align-middle">
                              <thead>
                                <tr>
                                  <th scope="col">Item</th>
                                  <th scope="col"style="min-width: 150px;">Part Number</th>
                                  <th scope="col">Material</th>
                                  <th scope="col" style="min-width: 150px;">Length & Unit</th>
                                  <th scope="col" style="min-width: 150px;">Width & Unit</th>
                                  <th scope="col" style="min-width: 150px;">Thick & Unit</th>
                                  <th scope="col" style="min-width: 150px;">QTY</th>
                                  <th scope="col" style="min-width: 150px;">Total QTY</th>
                                  <th scope="col" style="min-width: 150px;">Order Size Same as BOM</th>
                                  <th scope="col" style="min-width: 150px;">Order Length</th>
                                  <th scope="col" style="min-width: 150px;">Order Width</th>
                                  <th scope="col" style="min-width: 150px;">Order Thick</th>
                                  <th scope="col" style="min-width: 150px;">Order QTY</th>
                                  <th scope="col" style="min-width: 150px;">Loc. Receive</th>
                                  <th scope="col" style="min-width: 150px;">Loc. Produce</th>
                                  <th scope="col" style="min-width: 150px;">Loc. Loading</th>
                                  <th scope="col" style="min-width: 150px;">Remarks</th>
                                  <th scope="col" style="min-width: 100px;">Status</th>
                                  <th scope="col" style="min-width: 100px;">Change</th>
                                  <th scope="col" style="min-width: 86px;">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>

                          @if( Auth::user()->isPermissionAllowed('bom-list.edit') )
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="overwrite_bom_mapping" name="overwrite_bom_mapping">
                            <label class="form-check-label" for="overwrite_bom_mapping">
                              Overwrite Bom Mapping with the above List
                            </label>
                          </div>

                          <div class="mt-2">
                            <input type="hidden" class="form-control" name="order_id" id="bom_order_id" value="0">
                            <input type="hidden" class="form-control" name="product_id" id="bom_product_id" value="0">
                            <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Save</button>
                          </div>
                          @endif
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
<div class="modal fade" id="addMultipleProducts" tabindex="-1" aria-labelledby="addMultipleProducts" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanQRCodeStockCardProductionModal1Label">Select Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="multi-product-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><input onclick="$('#multi-product-table tbody input[type=checkbox]').prop('checked',this.checked)" type="checkbox" id="selectAll"></th>
                            <th>Product Number</th>
                            <th>Item</th>
                            <th>Material</th>
                            <th>Category</th>
                        </tr>
                        <tr>
                          <th></th>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                    </thead>
                    <tbody id="table-multiple-data">
                    </tbody>
                </table>
                <button id="AddItemsInTable" class="btn btn-success" onclick="addToBom()">Add Items</button>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
@section('custom_js')
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
<script>
  var jobProductsList;
  var combineQtyForCalculation=0;
  var unitsHtml = '';
  var unitsArr = [];
  var oid;
  var pid;
  @foreach ($units as $unit)
  unitsArr.push('{{ $unit->name }}');
  unitsHtml += `<option value="{{ $unit->name }}">{{ $unit->name }}</option>`;
  @endforeach

  var currentJobOrderProducts = null;
  var jobOrderProductDetails = null;
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
    oid=jobOrderId;
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
    $("#AddItemsInTable_").show();
    pid=$(".job-orders-products").val();
    

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
    var routeUrl = "{{ route('job-order.bom.list', ['order_id'=> ':order_id', 'product_id'=> ':product_id']) }}";
    routeUrl = routeUrl.replace(':order_id', selectedJobOrder);
    routeUrl = routeUrl.replace(':product_id', selectedProduct);

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        addTableData();
        var lastCat = 0;
        var lastParentProductId = 0;
        var bomListHtmlData = '';
        $('.BOMProductsList tbody').html('');

        $.each(response, function( index, bomItem ) {
          console.log(bomItem);
            // Heading
            isBomCat = false;
            if(bomItem.item.parent_product == null && (bomItem.item.category.has_bom_items == false || bomItem.item.category.has_bom_items == '0')) {
            // Parent Product Heading
            bomListHtmlData += `
            <tr data-parent-product-id="${bomItem.item.id}" class="rowHeading">
              <td class="fs-6 text-white bg-secondary">
                <input type="hidden" class="form-control" name="item_id[]" value="${bomItem.item.id}">
                <b>${bomItem.item.model_name}</b>
              </td>
              <td class="fs-6 text-white bg-secondary">
                <b>${bomItem.item.product_name}</b>
              </td>
              <td colspan="4" class="fs-6 text-white bg-secondary"></td>
              <td class="fs-6 text-white bg-secondary">
                <input type="number" class="form-control qtyInput" name="quantity[]" value="${bomItem.quantity}" required readonly>
              </td>
              <td class="fs-6 text-white bg-secondary">
                <input type="number" class="form-control" name="total_quantity[]" value="${bomItem.quantity}" required
                  readonly>
              </td>
              <td colspan="10" class="bg-secondary">
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
            
                  <input type="text" class="form-control form-control-sm" name="order_quantity[]">
            
                  <input type="hidden" name="order_size_same_as_bom[]" value="0">
            
                  <input type="text" class="form-control form-control-sm" name="order_length[]">
                  <select class="form-select form-select-sm" id="length_unit" name="order_length_unit[]">
                    <option></option>
                  </select>
            
                  <input type="text" class="form-control form-control-sm" name="order_width[]">
                  <select class="form-select form-select-sm" id="width_unit" name="order_width_unit[]">
                    <option></option>
                  </select>
            
                  <input type="text" class="form-control form-control-sm" name="order_thick[]">
                  <select class="form-select form-select-sm" id="thick_unit" name="order_thick_unit[]">
                    <option></option>
                  </select>
            
                  <input type="text" class="form-control form-control-sm" name="location_receiving[]">
                  <input type="text" class="form-control form-control-sm" name="location_produce[]">
                  <input type="text" class="form-control form-control-sm" name="location_loading[]">
                  <input type="text" class="form-control form-control-sm" name="remarks[]">
                  <input type="hidden" name="status[]" value="0">
                </div>
              </td>
              <td class="bg-secondary">
                <select class="form-select form-select-sm isChangeSelectBox dontAffectChange" id="is_change" name="is_change[]">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </td>
              <td class="bg-secondary"></td>
            </tr>`;
            lastParentProductId = bomItem.item.id;

          } else {
            // Items
            if(bomItem.item.category.has_bom_items == "1") {
              if(bomItem.item.category.id !== lastCat) {
                // BOM Category Heading
                bomListHtmlData += `
                <tr data-bom-cat-id="${bomItem.item.category_id}" class="rowHeading">
                  <td colspan="21" class="fs-5 text-white text-center bg-info">
                    <b>${bomItem.item.category.name}</b>
                  </td>
                </tr>`;
              }
              lastCat = bomItem.item.category.id;
            }

            var currentLengthUnitHtml = '';
            var currentWidthUnitHtml = '';
            var currentThickUnitHtml = '';
            
            $.each(unitsArr, function( index, unit ) {
            
              if(bomItem.item.length_unit == unit) {
                currentLengthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
              } else {
                currentLengthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
              }
              
              if(bomItem.item.width_unit == unit) {
                currentWidthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
              } else {
                currentWidthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
              }
              
              if(bomItem.item.thick_unit == unit) {
                currentThickUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
              } else {
                currentThickUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
              }
            
            });
            
            bomListHtmlData += `
            <tr>
              <td>
                <input type="hidden" class="form-control" name="item_id[]" value="${bomItem.item_id}">
                ${bomItem.item.model_name ?? "N/A"}
              </td>
              <td>${bomItem.item.product_name ?? "N/A"}</td>
              <td>${bomItem.item.material ?? "N/A"}</td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm lengthInput" name="length[]"
                    value="${bomItem.item.length ?? ''}" readonly>
                  <span class="input-group-text">
                    <select class="form-select form-select-sm" id="length_unit" name="length_unit[]" readonly
                      style="pointer-events: none; background: #e9ecef;">
                      <option></option>
                      ${currentLengthUnitHtml}
                    </select>
                  </span>
                </div>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm widthInput" name="width[]"
                    value="${bomItem.item.width ?? ''}" readonly>
                  <span class="input-group-text">
                    <select class="form-select form-select-sm" id="width_unit" name="width_unit[]" readonly
                      style="pointer-events: none; background: #e9ecef;">
                      <option></option>
                      ${currentWidthUnitHtml}
                    </select>
                  </span>
                </div>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm thickInput" name="thick[]"
                    value="${bomItem.item.thick ?? ''}" readonly>
                  <span class="input-group-text">
                    <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]" readonly
                      style="pointer-events: none; background: #e9ecef;">
                      <option></option>
                      ${currentThickUnitHtml}
                    </select>
                  </span>
                </div>
              </td>
              <td>
                <input type="number" class="form-control qtyInput" name="quantity[]" value="${bomItem.quantity ?? ''}" required
                  readonly>
              </td>
              <td>
                <input type="number" class="form-control totalQtyInput" name="total_quantity[]"
                  value="${bomItem.quantity ?? ''}" readonly required>
              </td>
              <td class="text-center">
                <select class="form-select form-select-sm" id="order_size_same_as_bom" name="order_size_same_as_bom[]" readonly
                  style="pointer-events: none; background: #e9ecef;">
                  <option value="1" ${bomItem.order_size_same_as_bom==1 ? 'selected' : '' }>Yes</option>
                  <option value="0" ${bomItem.order_size_same_as_bom==0 ? 'selected' : '' }>No</option>
                </select>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm orderLengthInput" name="order_length[]"
                    value="${bomItem.order_length ?? (bomItem.length ?? '')}" readonly>
                  <span class="input-group-text">
                    <select class="form-select form-select-sm" id="length_unit" name="order_length_unit[]" readonly
                      style="pointer-events: none; background: #e9ecef;">
                      <option></option>
                      ${currentLengthUnitHtml}
                    </select>
                  </span>
                </div>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm orderWidthInput" name="order_width[]"
                    value="${ bomItem.order_width ?? (bomItem.width ?? '')}" readonly>
                  <span class="input-group-text">
                    <select class="form-select form-select-sm" id="width_unit" name="order_width_unit[]" readonly
                      style="pointer-events: none; background: #e9ecef;">
                      <option></option>
                      ${currentWidthUnitHtml}
                    </select>
                  </span>
                </div>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm orderThickInput" name="order_thick[]"
                    value="${bomItem.order_thick ?? (bomItem.thick ?? '')}" readonly>
                  <span class="input-group-text">
                    <select class="form-select form-select-sm" id="thick_unit" name="order_thick_unit[]" readonly
                      style="pointer-events: none; background: #e9ecef;">
                      <option></option>
                      ${currentThickUnitHtml}
                    </select>
                  </span>
                </div>
              </td>
              <td>
                <input type="number" class="form-control orderQtyInput" name="order_quantity[]"
                  value="${bomItem.order_quantity ?? ''}" readonly>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm" name="location_receiving[]"
                    value="${bomItem.location_receiving ?? ''}" readonly>
                </div>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm" name="location_produce[]"
                    value="${bomItem.location_produce ?? ''}" readonly>
                </div>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm" name="location_loading[]"
                    value="${bomItem.location_loading ?? ''}" readonly>
                </div>
              </td>
              <td>
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control form-control-sm" name="remarks[]" value="${bomItem.remarks ?? ''}"
                    readonly>
                </div>
              </td>
              <td class="text-center">
                <select class="form-select form-select-sm statusSelectBox" id="status" name="status[]">
                  <option value="0" ${bomItem.status==0 ? 'selected' : '' }>No</option>
                  <option value="1" ${bomItem.status==1 ? 'selected' : '' }>Yes</option>
                </select>
              </td>
              <td class="text-center">
                <select class="form-select form-select-sm isChangeSelectBox dontAffectChange" id="is_change" name="is_change[]">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </td>
              <td>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${bomItem.item_id}"><i
                    class="far fa-eye"></i></a>
                @if( Auth::user()->isPermissionAllowed('bom-list.delete') )
                <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
                @endif
              </td>
            </tr>
            `;

          }

        });

        $('.BOMProductsList tbody').append(bomListHtmlData);

        combine_models();

      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });

  });

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
  function addToBom() {
    var productId = [];
      $('#multi-product-table tbody input[type=checkbox]:checked').each(function(a,b){
          productId.push($(b).val())
      })

    if(productId == '' || productId.length == 0) {
      alert('Select A product First');
      return;
    }

    var routeUrl = "{{ route('products.show', ':id') }}";
    routeUrl = routeUrl.replace(':id', productId);
    
    $.get( routeUrl, function(data) {
      $("#addMultipleProducts").modal('toggle');
                $('#multi-product-table tbody input[type=checkbox]:checked').each(function(a,b){
          $(b).prop("checked", false);
      })
      populateInsideBomList(data);
    })
    .fail(function() {
      console.log( "error" );
    });
  }

  function populateInsideBomList(productData) {
    var selectedJobOrder = $(".ajax-job-orders").val();
    var selectedProduct = $(".job-orders-products").val();
    
    // Find Job Order Product Details
    $.each(currentJobOrderProducts, function( index, productOrder ) {
      if( productOrder.product_id == selectedProduct && productOrder.order_id == selectedJobOrder ) {
        jobOrderProductDetails = productOrder;
        return false;
      }
    });

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

    // Heading
    isBomCat = false;

    var bomListHeadingHtmlData = '';
    var bomListHtmlData = '';

    if(productData.parent_id !== null) {
      // Parent Product Heading
      bomListHeadingHtmlData += `
        <tr data-parent-product-id="${productData.parent_product.id}" class="rowHeading">
            <td class="fs-6 text-white bg-secondary">
                <input type="hidden" class="form-control" name="item_id[]" value="${productData.parent_product.id}">
                <b>${productData.parent_product.model_name}</b>
            </td>
            <td class="fs-6 text-white bg-secondary">
                <b>${productData.parent_product.product_name}</b>
            </td>
            <td colspan="5" class="fs-6 text-white bg-secondary"></td>
            <td class="fs-6 text-white bg-secondary">
                <input type="number" class="form-control qtyInput" name="quantity[]" value="1" required>
            </td>
            <td class="fs-6 text-white bg-secondary">
                <input type="number" class="form-control" name="total_quantity[]" value="${1 * jobOrderProductDetails.quantity}"
                    required>
            </td>
            <td colspan="9" class="bg-secondary">
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
        
                    <input type="text" class="form-control form-control-sm" name="order_quantity[]">
        
                    <input type="text" class="form-control form-control-sm" name="order_length[]">
                    <select class="form-select form-select-sm" id="length_unit" name="order_length_unit[]">
                        <option></option>
                    </select>
        
                    <input type="text" class="form-control form-control-sm" name="order_width[]">
                    <select class="form-select form-select-sm" id="width_unit" name="order_width_unit[]">
                        <option></option>
                    </select>
        
                    <input type="text" class="form-control form-control-sm" name="order_thick[]">
                    <select class="form-select form-select-sm" id="thick_unit" name="order_thick_unit[]">
                        <option></option>
                    </select>
        
                    <input type="text" class="form-control form-control-sm" name="location_receiving[]">
                    <input type="text" class="form-control form-control-sm" name="location_produce[]">
                    <input type="text" class="form-control form-control-sm" name="location_loading[]">
                    <input type="text" class="form-control form-control-sm" name="remarks[]">
                    <input type="hidden" name="status[]" value="0">
                    <input type="hidden" name="order_size_same_as_bom[]" value="0">
                </div>
            </td>
            <td class="bg-secondary">
                -
            </td>
            <td class="bg-secondary" colspan="2"></td>
        </tr>`;
    }

    if(productData.category_id !== null) {
      // Items
      if(productData.category.has_bom_items == "1") {
        isBomCat= true;
        // BOM Category Heading
        bomListHeadingHtmlData += `
        <tr data-bom-cat-id="${productData.category_id}" class="rowHeading">
            <td colspan="21" class="fs-5 text-white text-center bg-info">
                <b>${productData.category.name}</b>
            </td>
        </tr>`;
      }
    }

    bomListHtmlData += `
    <tr>
        <td>
            <input type="hidden" class="form-control" name="item_id[]" value="${productData.id}">
            ${productData.model_name ?? "N/A" }
        </td>
        <td>${productData.product_name ?? "N/A" }</td>
        <td>${productData.material ?? "N/A" }</td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm lengthInput" name="length[]"
                    value="${productData.length ?? ''}" readonly>
                <span class="input-group-text">
                    <select class="form-select form-select-sm" id="length_unit" name="length_unit[]" readonly
                        style="pointer-events: none; background: #e9ecef;">
                        <option></option>
                        ${currentLengthUnitHtml}
                    </select>
                </span>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm widthInput" name="width[]"
                    value="${productData.width ?? ''}" readonly>
                <span class="input-group-text">
                    <select class="form-select form-select-sm" id="width_unit" name="width_unit[]" readonly
                        style="pointer-events: none; background: #e9ecef;">
                        <option></option>
                        ${currentWidthUnitHtml}
                    </select>
                </span>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm thickInput" name="thick[]"
                    value="${productData.thick ?? ''}" readonly>
                <span class="input-group-text">
                    <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]" readonly
                        style="pointer-events: none; background: #e9ecef;">
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
            <input type="number" class="form-control totalQtyInput" name="total_quantity[]"
                value="${1 * jobOrderProductDetails.quantity}" required>
        </td>
        <td>
            <select class="form-select form-select-sm" id="order_size_same_as_bom" name="order_size_same_as_bom[]">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm orderLengthInput" name="order_length[]"
                    value="${productData.order_length ?? ''}">
                <span class="input-group-text">
                    <select class="form-select form-select-sm" id="length_unit" name="order_length_unit[]">
                        <option></option>
                        ${currentLengthUnitHtml}
                    </select>
                </span>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm orderWidthInput" name="order_width[]"
                    value="${productData.order_width ?? ''}">
                <span class="input-group-text">
                    <select class="form-select form-select-sm" id="width_unit" name="order_width_unit[]">
                        <option></option>
                        ${currentWidthUnitHtml}
                    </select>
                </span>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm orderThickInput" name="order_thick[]"
                    value="${productData.order_thick ?? ''}">
                <span class="input-group-text">
                    <select class="form-select form-select-sm" id="thick_unit" name="order_thick_unit[]">
                        <option></option>
                        ${currentThickUnitHtml}
                    </select>
                </span>
            </div>
        </td>
        <td>
            <input type="number" class="form-control orderQtyInput" name="order_quantity[]" value="1">
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_receiving[]"
                    value="${productData.location_receiving ?? ''}">
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_produce[]"
                    value="${productData.location_produce ?? ''}">
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_loading[]"
                    value="${productData.location_loading ?? ''}">
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="remarks[]"
                    value="${productData.remarks ?? ''}">
            </div>
        </td>
        <td class="text-center">
            <select class="form-select form-select-sm statusSelectBox statusCheckBox" id="status" name="status[]">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </td>
        <td class="text-center">
            -
        </td>
        <td>
            <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${productData.id}"><i
                    class="far fa-eye"></i></a>
    
            @if( Auth::user()->isPermissionAllowed('bom-list.delete') )
            <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
            @endif
        </td>
    </tr>
    `;

    if(isBomCat && productData.category_id == null) {
      alert('invalid product selected that dont have any category');
      return;
    }
    if(!isBomCat && productData.parent_id == null) {
      alert('invalid product selected that dont have any parent product');
      return;
    }

    if(isBomCat) {
        var isHeadingExists = checkIfCategoryExists(productData.category_id);
    } else {
        var isHeadingExists = checkIfParentProductExists(productData.parent_id);
    }

    var htmlBody = '';

    // Add new Heading Row
    if(isHeadingExists == false) {
        htmlBody += bomListHeadingHtmlData;
        htmlBody += bomListHtmlData;
        $('.BOMProductsList tbody').append(htmlBody); 
    } else {
      htmlBody += bomListHtmlData;
      $(".BOMProductsList tbody tr:nth-child(" + (isHeadingExists+1) + ")").after(htmlBody);
    }

    // Reset Select2 Input
    $(".ajax-products").val('').trigger('change');
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

    $('body').on("change",'.statusSelectBox', function() {
      // if ($(this).val() == 1) {
      //   $(this).siblings("input[type=hidden]").prop('disabled', true);
      //   $(this).siblings("input[type=hidden]").attr('disabled', true);
      // } else {
      //   $(this).siblings("input[type=hidden]").val();
      //   $(this).siblings("input[type=hidden]").prop('disabled', false);
      //   $(this).siblings("input[type=hidden]").attr('disabled', false);
      // }
    });

    $('body').on("change",'.isChangeSelectBox', function() {
      $("#order_size_same_as_bom").change();
      
      tdHtmlsArr = $(this).parent().parent().find('td');
      
      if ($(this).val() == 1) {
        $.each(tdHtmlsArr, function( index, tdEle ) {
          var input = $(tdEle).find('input');
          var select = $(tdEle).find('select');

          if(input.length > 0) {
            if(!$(input).hasClass("dontAffectChange")) {
              $(input).prop('readonly', false);
              $(input).attr('readonly', false);
              $(input).removeAttr('style');
            }
          }
          if(select.length > 0) {
            if(!$(input).hasClass("dontAffectChange")) {
              $(select).prop('readonly', false);
              $(select).attr('readonly', false);
              $(select).removeAttr('style');
            }
          }
        });
      } else {
        $.each(tdHtmlsArr, function( index, tdEle ) {
          var input = $(tdEle).find('input');
          var select = $(tdEle).find('select');

          if(input.length > 0) {
            if(!$(input).hasClass("dontAffectChange")) {
              $(input).prop('readonly', true);
              $(input).attr('readonly', true);
              if(!$(input).hasClass("statusSelectBox")) {
                $(input).attr('style', 'pointer-events: none; background: #e9ecef;');
              } else {
                $(input).attr('style', 'pointer-events: none;');
              }
            }
          }
          if(select.length > 0) {
            if(!$(select).hasClass("dontAffectChange")) {
              $(select).prop('readonly', true);
              $(select).attr('readonly', true);
              $(select).attr('style', 'pointer-events: none; background: #e9ecef;');
            }
          }
        });
      }
    });
  });

  @if(!empty($jobOrder))
  $(document).ready(function(){
    $('.ajax-job-orders').trigger("change");
  });
  @endif

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
          totalQty += parseInt(jobProduct.quantity);
        }
      });
      combineQtyForCalculation = parseInt(totalQty);
      combineAllQuantities(totalQty);
    } else {
      $('.combine_models_bom_hidden').val('0');
      $.each(jobProductsList, function( index, jobProduct ) {
        if(jobProduct.product.id == selectedModelId) {
          combineQtyForCalculation = parseInt(jobProduct.quantity);
          combineAllQuantities(parseInt(jobProduct.quantity));
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

  $('body').on("change",'#order_size_same_as_bom', function() {
    var orderSizeSameAsBOM = $(this).val();
    var lengthInput = parseInt($(this).parent().parent().find('.lengthInput').val());
    var widthInput = parseInt($(this).parent().parent().find('.widthInput').val());
    var thickInput = parseInt($(this).parent().parent().find('.thickInput').val());
    var orderLengthInput = parseInt($(this).parent().parent().find('.orderLengthInput').val());
    var orderWidthInput = parseInt($(this).parent().parent().find('.orderWidthInput').val());
    var orderThickInput = parseInt($(this).parent().parent().find('.orderThickInput').val());
    var totalQtyInput = parseInt($(this).parent().parent().find('.totalQtyInput').val());
    if(orderSizeSameAsBOM == 0) {
      // var orderQty = Math.round( totalQtyInput );
      // var orderQty = ( totalQtyInput / ( ( orderLengthInput/lengthInput ) * ( orderWidthInput/widthInput ) ) );
      // orderQty = Math.round(orderQty);
      // $(this).parent().parent().find('.orderQtyInput').val(orderQty);
    } else {
      $(this).parent().parent().find('.orderLengthInput').val(lengthInput);
      $(this).parent().parent().find('.orderWidthInput').val(widthInput);
      $(this).parent().parent().find('.orderThickInput').val(thickInput);
      $(this).parent().parent().find('.orderQtyInput').val(totalQtyInput);
    }
  });

  $('body').on("change",'.totalQtyInput, .qtyInput', function() {
    var orderSizeSameAsBOM = $(this).parent().parent().find('#order_size_same_as_bom').val();
    var lengthInput = parseInt($(this).parent().parent().find('.lengthInput').val());
    var widthInput = parseInt($(this).parent().parent().find('.widthInput').val());
    var thickInput = parseInt($(this).parent().parent().find('.thickInput').val());
    var orderLengthInput = parseInt($(this).parent().parent().find('.orderLengthInput').val());
    var orderWidthInput = parseInt($(this).parent().parent().find('.orderWidthInput').val());
    var orderThickInput = parseInt($(this).parent().parent().find('.orderThickInput').val());
    var totalQtyInput = parseInt($(this).parent().parent().find('.totalQtyInput').val());

    if($(this).hasClass('qtyInput')) {
      var currentQty = $(this).val();
      totalQtyInput *= currentQty;
    }

    if(orderSizeSameAsBOM == 0) {
      // var orderQty = Math.round( totalQtyInput );
      // var orderQty = ( totalQtyInput / ( ( orderLengthInput/lengthInput ) * ( orderWidthInput/widthInput ) ) );
      // orderQty = Math.round(orderQty);
      // $(this).parent().parent().find('.orderQtyInput').val(orderQty);
    } else {
      $(this).parent().parent().find('.orderLengthInput').val(lengthInput);
      $(this).parent().parent().find('.orderWidthInput').val(widthInput);
      $(this).parent().parent().find('.orderThickInput').val(thickInput);
      $(this).parent().parent().find('.orderQtyInput').val(totalQtyInput);
    }
  });

  $('body').on("change",'lengthInput, .widthInput, .thickInput, .orderLengthInput, .orderWidthInput, .orderThickInput', function() {
    var orderSizeSameAsBOM = $(this).parent().parent().parent().find('#order_size_same_as_bom').val();
    var lengthInput = parseInt($(this).parent().parent().parent().find('.lengthInput').val());
    var widthInput = parseInt($(this).parent().parent().parent().find('.widthInput').val());
    var thickInput = parseInt($(this).parent().parent().parent().find('.thickInput').val());
    var orderLengthInput = parseInt($(this).parent().parent().parent().find('.orderLengthInput').val());
    var orderWidthInput = parseInt($(this).parent().parent().parent().find('.orderWidthInput').val());
    var orderThickInput = parseInt($(this).parent().parent().parent().find('.orderThickInput').val());
    var totalQtyInput = parseInt($(this).parent().parent().parent().find('.totalQtyInput').val());

    if(orderSizeSameAsBOM == 0) {
      // var orderQty = Math.round( totalQtyInput );
      // var orderQty = ( totalQtyInput / ( ( orderLengthInput/lengthInput ) * ( orderWidthInput/widthInput ) ) );
      // orderQty = Math.round(orderQty);
      // $(this).parent().parent().parent().find('.orderQtyInput').val(orderQty);
    } else {
      $(this).parent().parent().parent().find('.orderLengthInput').val(lengthInput);
      $(this).parent().parent().parent().find('.orderWidthInput').val(widthInput);
      $(this).parent().parent().parent().find('.orderThickInput').val(thickInput);
      $(this).parent().parent().parent().find('.orderQtyInput').val(totalQtyInput);
    }
  });
  function addTableData(){
    console.log("retert")
    $.ajax({
      url: window.location.origin+'/public/job-order/receiving/table-data',
      type: "get",
      success: function (response) {
        var tableData = "";
        for(var i = 0;i < response.length;i++){
          tableData += `<tr>
          <td><input value="${response[i].id}" type="checkbox"></td>
          <td>${response[i].product_name}</td>
          <td>${response[i].model_name}</td>
          <td>${response[i].material}</td>
          <td>${response[i].category}</td>
          </tr>`;
        }
        $("#table-multiple-data").html(tableData);
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
      }
  });
  }
</script>
@endsection