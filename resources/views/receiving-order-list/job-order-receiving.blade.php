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
            <h1 class="m-0 text-dark"><i class="fas fa-clipboard-check"></i> Manage Receiving Order List</h1>
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
                  <h4 class="card-title">Update Receiving Order List
                  <a href="{{ route('job-order.receiving.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
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
                      </div>
                      <button data-bs-toggle="modal" data-bs-target="#addMultipleProducts" id="AddItemsInTable_" class="btn btn-sm btn-primary" style="display: none;">Add Multiple Items</button>
                      <div class="col-md-12 bomListBox d-none">
                        <hr>
                        <h4>Receiving List:</h4>
                        <hr>

                        <form method="post" action="{{ route('job-order.receiving.store') }}" enctype="multipart/form-data">
                          @csrf

                          <div class="table-responsive BOMProductsList">
                            <table class="table table-striped table-hover table-bordered align-middle">
                              <thead>
                                <tr class="text-center">
                                  <th scope="col" colspan="23" class="bg-primary text-white fs-5">RECEIVING/ STORE</th>
                                  <th scope="col" colspan="10" class="bg-secondary fs-5 text-white">MEMO (IF HAVE REJECT)</th>
                                </tr>
                                <tr>
                                  <th scope="col">Item</th>
                                  <th scope="col">Part Number</th>
                                  <th scope="col">Material</th>
                                  <th scope="col">Length & Unit</th>
                                  <th scope="col">Width & Unit</th>
                                  <th scope="col">Thick & Unit</th>
                                  <th scope="col">Total QTY Ordered</th>
                                  <th scope="col">Order Length</th>
                                  <th scope="col">Order Width</th>
                                  <th scope="col">Order Thick</th>
                                  <th scope="col">Order QTY</th>
                                  <th scope="col">Previous QTY</th>
                                  <th scope="col">Balance</th>

                                  <th scope="col">Loc. Receive</th>
                                  <th scope="col">Loc. Produce</th>
                                  <th scope="col">Loc. Loading</th>

                                  <th scope="col">Date Order</th>

                                  <th scope="col">Date In</th>
                                  <th scope="col">DO NO</th>
                                  <th scope="col">Received Quantity</th>
                                  <th scope="col">FOC</th>
                                  <th scope="col">Balance</th>
                                  <th scope="col">Remarks</th>
                                  <th scope="col">Receive as well</th>
                                  <th scope="col">Remarks</th>
                                  
                                  <th scope="col">Reject</th>
                                  <th scope="col">Date Out</th>
                                  <th scope="col">Memo No</th>
                                  <th scope="col">Quantity</th>
                                  <th scope="col">Est. delievery date</th>
                                  <th scope="col">Receive as well</th>
                                  <th scope="col">Cause</th>
                                  <th scope="col">Photo</th>
                                  <th scope="col">Remarks</th>
                                  <th scope="col" style="min-width: 90px;">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>

                          <div class="mt-2">
                            <input type="hidden" class="form-control" name="order_id" id="bom_order_id" value="{{ $orderId }}">
                            <input type="hidden" class="form-control" name="product_id" id="bom_product_id" value="{{ $productId }}">
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
                <button id="AddItemsInTable" class="btn btn-success">Add Items</button>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
@section('custom_js')
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.js"></script>
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
  var jobProductsList = null;
  var order_id = {{ $jobOrder->id ?? 0 }};
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

  var unitsHtml = '';
  var unitsArr = [];
  var oid;
  var pid;
  @foreach ($units as $unit)
  unitsArr.push('{{ $unit->name }}');
  unitsHtml += `<option value="{{ $unit->name }}">{{ $unit->name }}</option>`;
  @endforeach

  var currentJobOrderProducts = null;
  var jobOrderProductDetails = null

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
    oid=jobOrderId;
    order_id = jobOrderId;

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

  $("#AddItemsInTable").on('click',function (){
      var selected = [];
      $('#multi-product-table tbody input[type=checkbox]:checked').each(function(a,b){
          selected.push($(b).val())
      })
      $.ajax({
          url: "{{ route('job-order.get-product-details')}}"+"?list="+selected.join(',')+"&orderId="+oid+"&pid="+pid,
          type: "get",
          success: function (response) {
            var lastCat = 0;
        var lastParentProductId = 0;
        var bomListHtmlData = '';
        $("#addMultipleProducts").modal('toggle');
              if(response){
                $('#multi-product-table tbody input[type=checkbox]:checked').each(function(a,b){
          $(b).prop("checked", false);
      })
                $.each(response, function( index, purchaseItem ) {
          

          if(purchaseItem.purchase !== undefined) {
            purchaseItemRow = purchaseItem.purchase;
          } else {
            purchaseItemRow = purchaseItem;
          }

          if(purchaseItemRow.item.category.has_bom_items ===1) {
            if(purchaseItemRow.item.bomcategory_id !== lastCat) {
              // BOM Category Heading
              bomListHtmlData += `
              <tr data-bom-cat-id="${purchaseItemRow.item.bomcategory_id}" class="rowHeading">
                <td colspan="34" class="fs-5 text-white text-center bg-info">
                  <b>${purchaseItemRow.item.category.name}</b>
                </td>
              </tr>`;
            }
            lastCat = purchaseItemRow.item.bomcategory_id;
          }

          if(purchaseItemRow.item.parent_id !== null) {
            isBomCat = false;
            if(purchaseItemRow.item.parent_id !== lastParentProductId) {
              // Parent Product Heading
              bomListHtmlData += `
              <tr data-parent-product-id="${purchaseItemRow.item.parent_id}" class="rowHeading">
                <td class="fs-6 text-white bg-secondary">
                  <b>${purchaseItemRow.item.parent_product.model_name}</b>
                </td>
                <td class="fs-6 text-white bg-secondary">
                  <b>${purchaseItemRow.item.parent_product.product_name}</b>
                </td>
                <td colspan="4" class="fs-6 text-white bg-secondary">
                </td>
                <td class="fs-6 text-white bg-secondary">
                  <input type="number" class="form-control" value="${purchaseItemRow.quantity ?? purchaseItemRow.bom_quantity}" disabled>
                </td>
                <td class="fs-6 text-white bg-secondary">
                  <input type="number" class="form-control" value="${purchaseItemRow.total_quantity ?? purchaseItemRow.bom_total_quantity}" disabled>
                </td>
                <td colspan="22" class="bg-secondary">
                  <div class="d-none">

                  </div>
                </td>
              </tr>`;
            }
            lastParentProductId = purchaseItemRow.item.parent_id;
          }

          if(purchaseItemRow.item.bomcategory_id === null && purchaseItemRow.item.parent_product === null) {
            return;
          }

          var currentLengthUnitHtml = '';
          var currentWidthUnitHtml = '';
          var currentThickUnitHtml = '';

          $.each(unitsArr, function( index, unit ) {

            if(purchaseItemRow.item.length_unit == unit) {
              currentLengthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
            } else {
              currentLengthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
            }

            if(purchaseItemRow.item.width_unit == unit) {
              currentWidthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
            } else {
              currentWidthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
            }

            if(purchaseItemRow.item.thick_unit == unit) {
              currentThickUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
            } else {
              currentThickUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
            }

          });
            repeat.push(purchaseItemRow.item.product_name);
          bomListHtmlData += `
          <tr data-bom-item-id="${purchaseItemRow.item_id}">
            <td>
              <input type="hidden" class="form-control" name="item_id[]" value="${purchaseItemRow.item_id}">
              <input type="hidden" class="form-control" name="purchase_id[]" value="${purchaseItemRow.id}">
              ${purchaseItemRow.item.model_name ?? "N/A"}
            </td>
            <td>${purchaseItemRow.item.product_name ?? "N/A"}</td>
            <td>${purchaseItemRow.item.material ?? "N/A"}</td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.item.length ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="length_unit" disabled>
                    <option></option>
                    ${currentLengthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.item.width ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="width_unit" disabled>
                    <option></option>
                    ${currentWidthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.item.thick ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="thick_unit" disabled>
                    <option></option>
                    ${currentThickUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <input type="number" class="form-control" value="${purchaseItemRow.bom_total_quantity ?? ''}" name="ordered_quantity[]" readonly>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.order_length ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="length_unit" disabled>
                    <option></option>
                    ${currentLengthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.order_width ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="width_unit" disabled>
                    <option></option>
                    ${currentWidthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.order_thick ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="thick_unit" disabled>
                    <option></option>
                    ${currentThickUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <input type="number" class="form-control orderQty" value="${purchaseItemRow.bom_order_quantity}" name="bom_order_quantity[]" readonly>
            </td><td>
              <input type="number" class="form-control orderQty" value="0" name="bom_order_quantity[]" readonly>
            </td><td>
              <input type="number" class="form-control orderQty" value="${purchaseItemRow.bom_order_quantity-0}"  readonly>
            </td><td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_receiving[]"
                  value="${purchaseItem.location_receiving ?? ''}">
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_produce[]"
                  value="${purchaseItem.location_produce ?? ''}">
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_loading[]"
                  value="${purchaseItem.location_loading ?? ''}">
              </div>
            </td>

            <td>
              <input type="text" class="form-control" value="${purchaseItemRow.order_date ?? ''}" disabled>
            </td>

            <td>
              <input type="text" class="form-control datepicker" name="date_in[]" value="${purchaseItem.date_in ?? ''}">
            </td>
            <td>
              <input type="text" class="form-control" name="do_no[]" value="${purchaseItem.do_no ?? ''}">
            </td>
            <td>
              <input type="number" class="form-control qtyRecInput" ${parseInt(purchaseItem.is_updated) ? 'readonly' : ''} name="received_quantity[]" value="${purchaseItem.received_quantity ?? ''}" required>
            </td>
            <td>
              <input type="number" class="form-control" ${parseInt(purchaseItem.is_updated) ? 'readonly' : ''} name="extra_less[]" value="${purchaseItem.extra_less ?? '0'}" required>
            </td>
            <td>
              <input type="hidden" name="balance_received_as_well[]" value="0" ${purchaseItem.balance_received_as_well==1 ? 'disabled' : '' }>
              <input type="number" class="form-control qtyBalInput" ${parseInt(purchaseItem.is_updated) ? 'readonly' : ''} name="balance[]" value="${purchaseItem.balance ?? ''}" required>
            </td>
            <td>
              <input type="text" class="form-control" name="receiving_remarks[]" value="${purchaseItem.receiving_remarks ?? ''}">
            </td>
            <td class="text-center">
              <input type="hidden" name="receive_as_well[]" value="0" ${purchaseItem.receive_as_well == 1 ? 'disabled' : ''}>
              <input class="form-check-input receiveAsWellCheckBox" type="checkbox" value="1" ${purchaseItem.receive_as_well == 1 ? 'checked' : ''} name="receive_as_well[]">
            </td>
            <td>
              <input type="text" class="form-control" name="receiving_remarks_s[]" value="${purchaseItem.receiving_remarks_s ?? ''}">
            </td>

            <td class="text-center">
              <input type="hidden" name="send_to_reject[]" value="0" ${purchaseItem.send_to_reject==1 ? 'disabled' : '' }>
              <input class="form-check-input sendToRejectCheckBox" type="checkbox" value="1"
                ${purchaseItem.send_to_reject==1 ? 'checked' : '' } name="send_to_reject[]">
            </td>
            <td>
              <input type="text" class="form-control datepicker" name="reject_date_out[]" value="${purchaseItem.reject_date_out ?? ''}" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>
            </td>
            <td>
              <input type="text" class="form-control" name="reject_memo_no[]" value="${purchaseItem.reject_memo_no ?? ''}" ${purchaseItem.send_to_reject==1 ? '' : 'readonly style="pointer-events: none;"' }>
            </td>
            <td>
              <input type="number" class="form-control" name="reject_quantity[]" value="${purchaseItem.reject_quantity ?? ''}" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>
            </td>
            <td>
              <input type="text" class="form-control datepicker" name="reject_est_delievery_date[]" value="${purchaseItem.reject_est_delievery_date ?? ''}" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>
            </td>
            <td class="text-center">
              <input type="hidden" name="reject_receive_as_well[]" value="0" ${purchaseItem.reject_receive_as_well == 1 ? 'disabled' : ''}>
              <input class="form-check-input receiveAsWellCheckBox" type="checkbox" value="1" ${purchaseItem.reject_receive_as_well == 1 ? 'checked' : ''} name="reject_receive_as_well[]" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>

              <a href="javascript:void(0)" class="btn btn-sm btn-primary ms-3 generateRejectStockCard"
                data-product-id="${purchaseItemRow.item_id}" title="View Stock Card"><i class="fas fa-print"></i></a>
            </td>
            <td>
              <input type="text" class="form-control" name="reject_cause[]" value="${purchaseItem.reject_cause ?? ''}">
            </td>
            <td>
              <input type="file" class="form-control" name="reject_picture_link[]" value="${purchaseItem.reject_picture_link ?? ''}">

              ${ (purchaseItem.reject_picture_link != undefined && purchaseItem.reject_picture_link != '' && purchaseItem.reject_picture_link != null ) ?
              `<img src="{{ Url('') }}/uploads/${purchaseItem.reject_picture_link}" height="100px">`
              : ''}
            </td>
            <td>
              <input type="text" class="form-control" name="reject_remarks[]" value="${purchaseItem.reject_remarks ?? ''}">
            </td>

            <td>
              <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${purchaseItemRow.item_id}"><i
                  class="far fa-eye"></i></a>

              <a href="javascript:void(0)" class="btn btn-sm btn-primary generateStockCard" data-product-id="${purchaseItemRow.item_id}"><i class="fas fa-print" title="View Stock Card"></i></a>
              <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
              </td>
          </tr>
          `;

        });

        $('.BOMProductsList tbody').append(bomListHtmlData);

        // Update Date Pickers
        initDatePicker();
              }else{
                  alert("Items adding in order failed")
              }
          },
          error: function(jqXHR, textStatus, errorThrown) {
              alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
          }
      });
  })
    var repeat = [];
  // Populate Bom Items fro Selected Job Order Model
  $(".job-orders-products").change(function() {
    $("#AddItemsInTable_").show();
    pid=$(".job-orders-products").val();
    addTableData();
    // console.log(jobOrderProductDetails)
    // addTableData(jobOrderProductDetails.product.id,jobOrderProductDetails.product.id);
    console.log("zain");
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
    `;

    // Show Job Order Product Details
    $(".productDetails ul").html(productDetailHtml);
    $(".productDetails").removeClass('d-none');

    // Show BOM List Box
    $(".bomListBox").removeClass('d-none');

    // Update Hidden Fields
    $("#bom_order_id").val(selectedJobOrder);
    $("#bom_product_id").val(selectedProduct);

    // Get Job Order Receiving List Items
    var routeUrl = "{{ route('job-order.receiving.list', ['order_id'=> ':order_id', 'product_id'=> ':product_id']) }}";
    routeUrl = routeUrl.replace(':order_id', selectedJobOrder);
    routeUrl = routeUrl.replace(':product_id', selectedProduct);

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        var lastCat = 0;
        var lastParentProductId = 0;
        var bomListHtmlData = '';
        $('.BOMProductsList tbody').html('');
        
        $.each(response.receivingList, function( index, purchaseItem ) {
          

          if(purchaseItem.purchase !== undefined) {
            purchaseItemRow = purchaseItem.purchase;
          } else {
            purchaseItemRow = purchaseItem;
          }

          if(purchaseItemRow.item.category.has_bom_items ===1) {
            if(purchaseItemRow.item.bomcategory_id !== lastCat) {
              // BOM Category Heading
              bomListHtmlData += `
              <tr data-bom-cat-id="${purchaseItemRow.item.bomcategory_id}" class="rowHeading">
                <td colspan="34" class="fs-5 text-white text-center bg-info">
                  <b>${purchaseItemRow.item.category.name}</b>
                </td>
              </tr>`;
            }
            lastCat = purchaseItemRow.item.bomcategory_id;
          }
          if(purchaseItemRow.item.parent_id !== null) {
            isBomCat = false;
            if(purchaseItemRow.item.parent_id !== lastParentProductId) {
              // Parent Product Heading
              bomListHtmlData += `
              <tr data-parent-product-id="${purchaseItemRow.item.parent_id}" class="rowHeading">
                <td class="fs-6 text-white bg-secondary">
                  <b>${purchaseItemRow.item.parent_product.model_name}</b>
                </td>
                <td class="fs-6 text-white bg-secondary">
                  <b>${purchaseItemRow.item.parent_product.product_name}</b>
                </td>
                <td colspan="4" class="fs-6 text-white bg-secondary">
                </td>
                <td class="fs-6 text-white bg-secondary">
                  <input type="number" class="form-control" value="${purchaseItemRow.quantity ?? purchaseItemRow.bom_quantity}" disabled>
                </td>
                <td class="fs-6 text-white bg-secondary">
                  <input type="number" class="form-control" value="${purchaseItemRow.total_quantity ?? purchaseItemRow.bom_total_quantity}" disabled>
                </td>
                <td colspan="22" class="bg-secondary">
                  <div class="d-none">

                  </div>
                </td>
              </tr>`;
            }
            lastParentProductId = purchaseItemRow.item.parent_id;
          }

          if(purchaseItemRow.item.bomcategory_id === null && purchaseItemRow.item.parent_product === null) {
            return;
          }

          var currentLengthUnitHtml = '';
          var currentWidthUnitHtml = '';
          var currentThickUnitHtml = '';

          $.each(unitsArr, function( index, unit ) {

            if(purchaseItemRow.item.length_unit == unit) {
              currentLengthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
            } else {
              currentLengthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
            }

            if(purchaseItemRow.item.width_unit == unit) {
              currentWidthUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
            } else {
              currentWidthUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
            }

            if(purchaseItemRow.item.thick_unit == unit) {
              currentThickUnitHtml += '<option value=' + unit + ' selected>' + unit + '</option>';
            } else {
              currentThickUnitHtml += '<option value=' + unit + '>' + unit + '</option>';
            }

          });

          bomListHtmlData += `
          <tr data-bom-item-id="${purchaseItemRow.item_id}">
            <td>
              <input type="hidden" class="form-control" name="item_id[]" value="${purchaseItemRow.item_id}">
              <input type="hidden" class="form-control" name="purchase_id[]" value="${purchaseItemRow.id}">
              ${purchaseItemRow.item.model_name ?? "N/A"}
            </td>
            <td>${purchaseItemRow.item.product_name ?? "N/A"}</td>
            <td>${purchaseItemRow.item.material ?? "N/A"}</td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.item.length ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="length_unit" disabled>
                    <option></option>
                    ${currentLengthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.item.width ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="width_unit" disabled>
                    <option></option>
                    ${currentWidthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.item.thick ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="thick_unit" disabled>
                    <option></option>
                    ${currentThickUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <input type="number" class="form-control" value="${purchaseItemRow.bom_total_quantity ?? ''}" name="ordered_quantity[]" readonly>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.order_length ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="length_unit" disabled>
                    <option></option>
                    ${currentLengthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.order_width ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="width_unit" disabled>
                    <option></option>
                    ${currentWidthUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" value="${purchaseItemRow.order_thick ?? ''}" disabled>
                <span class="input-group-text">
                  <select class="form-select form-select-sm" id="thick_unit" disabled>
                    <option></option>
                    ${currentThickUnitHtml}
                  </select>
                </span>
              </div>
            </td>
            <td>
              <input type="number" class="form-control orderQty" value="${purchaseItemRow.bom_order_quantity}" name="bom_order_quantity[]" readonly>
            </td>`;
            for(var i = 0; i < response.temp.length;i++){
            if(purchaseItemRow.item_id == response.temp[i].item_id){
              bomListHtmlData += `<td>
              <input type="number" class="form-control orderQty" value="${response.temp[i].received_quantity}" name="bom_order_quantity[]" readonly>
            </td><td>
              <input type="number" class="form-control orderQty" value="${purchaseItemRow.bom_order_quantity-response.temp[i].received_quantity}"  readonly>
            </td>`;
          }

          }
            bomListHtmlData +=`<td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_receiving[]"
                  value="${purchaseItem.location_receiving ?? ''}">
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_produce[]"
                  value="${purchaseItem.location_produce ?? ''}">
              </div>
            </td>
            <td>
              <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="location_loading[]"
                  value="${purchaseItem.location_loading ?? ''}">
              </div>
            </td>

            <td>
              <input type="text" class="form-control" value="${purchaseItemRow.order_date ?? ''}" disabled>
            </td>

            <td>
              <input type="text" class="form-control datepicker" name="date_in[]" value="${purchaseItem.date_in ?? ''}">
            </td>
            <td>
              <input type="text" class="form-control" name="do_no[]" value="${purchaseItem.do_no ?? ''}">
            </td>
            <td>
              <input type="number" class="form-control qtyRecInput" name="received_quantity[]" value="${purchaseItem.received_quantity ?? ''}" required>
            </td>
            <td>
              <input type="number" class="form-control" name="extra_less[]" value="${purchaseItem.extra_less ?? '0'}" required>
            </td>
            <td>
              <input type="hidden" name="balance_received_as_well[]" value="0" ${purchaseItem.balance_received_as_well==1 ? 'disabled' : '' }>
              <input class="form-check-input receiveAsWellCheckBox" type="checkbox" value="1" ${purchaseItem.balance_received_as_well==1
                ? 'checked' : '' } name="balance_received_as_well[]"> Received 
                <a href="javascript:void(0)" class="btn btn-sm btn-primary generateBalanceStockCard"
                  data-product-id="${purchaseItemRow.item_id}" title="View Stock Card"><i class="fas fa-print"></i></a>
              <input type="number" class="form-control qtyBalInput" name="balance[]" value="${purchaseItem.balance ?? ''}" required>
            </td>
            <td>
              <input type="text" class="form-control" name="receiving_remarks[]" value="${purchaseItem.receiving_remarks ?? ''}">
            </td>
            <td class="text-center">
              <input type="hidden" name="receive_as_well[]" value="0" ${purchaseItem.receive_as_well == 1 ? 'disabled' : ''}>
              <input class="form-check-input receiveAsWellCheckBox" type="checkbox" value="1" ${purchaseItem.receive_as_well == 1 ? 'checked' : ''} name="receive_as_well[]">
            </td>
            <td>
              <input type="text" class="form-control" name="receiving_remarks_s[]" value="${purchaseItem.receiving_remarks_s ?? ''}">
            </td>

            <td class="text-center">
              <input type="hidden" name="send_to_reject[]" value="0" ${purchaseItem.send_to_reject==1 ? 'disabled' : '' }>
              <input class="form-check-input sendToRejectCheckBox" type="checkbox" value="1"
                ${purchaseItem.send_to_reject==1 ? 'checked' : '' } name="send_to_reject[]">
            </td>
            <td>
              <input type="text" class="form-control datepicker" name="reject_date_out[]" value="${purchaseItem.reject_date_out ?? ''}" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>
            </td>
            <td>
              <input type="text" class="form-control" name="reject_memo_no[]" value="${purchaseItem.reject_memo_no ?? ''}" ${purchaseItem.send_to_reject==1 ? '' : 'readonly style="pointer-events: none;"' }>
            </td>
            <td>
              <input type="number" class="form-control" name="reject_quantity[]" value="${purchaseItem.reject_quantity ?? ''}" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>
            </td>
            <td>
              <input type="text" class="form-control datepicker" name="reject_est_delievery_date[]" value="${purchaseItem.reject_est_delievery_date ?? ''}" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>
            </td>
            <td class="text-center">
              <input type="hidden" name="reject_receive_as_well[]" value="0" ${purchaseItem.reject_receive_as_well == 1 ? 'disabled' : ''}>
              <input class="form-check-input receiveAsWellCheckBox" type="checkbox" value="1" ${purchaseItem.reject_receive_as_well == 1 ? 'checked' : ''} name="reject_receive_as_well[]" ${purchaseItem.send_to_reject == 1 ? '' : 'readonly style="pointer-events: none;"'}>

              <a href="javascript:void(0)" class="btn btn-sm btn-primary ms-3 generateRejectStockCard"
                data-product-id="${purchaseItemRow.item_id}" title="View Stock Card"><i class="fas fa-print"></i></a>
            </td>
            <td>
              <input type="text" class="form-control" name="reject_cause[]" value="${purchaseItem.reject_cause ?? ''}">
            </td>
            <td>
              <input type="file" class="form-control" name="reject_picture_link[]" value="${purchaseItem.reject_picture_link ?? ''}">

              ${ (purchaseItem.reject_picture_link != undefined && purchaseItem.reject_picture_link != '' && purchaseItem.reject_picture_link != null ) ?
              `<img src="{{ Url('') }}/uploads/${purchaseItem.reject_picture_link}" height="100px">`
              : ''}
            </td>
            <td>
              <input type="text" class="form-control" name="reject_remarks[]" value="${purchaseItem.reject_remarks ?? ''}">
            </td>

            <td>
              <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${purchaseItemRow.item_id}"><i
                  class="far fa-eye"></i></a>

              <a href="javascript:void(0)" class="btn btn-sm btn-primary generateStockCard" data-product-id="${purchaseItemRow.item_id}"><i class="fas fa-print" title="View Stock Card"></i></a>
            </td>
          </tr>
          `;

        });

        $('.BOMProductsList tbody').append(bomListHtmlData);
        
        // Update Date Pickers
        initDatePicker();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });

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

  $(document).on('click', '.viewProductBtn', function() {
    var productId = $(this).attr('data-id');
    var routeUrl = "{{ route('products.show', ':id')}}";
    routeUrl = routeUrl.replace(':id', productId);
    window.open(routeUrl, '_blank');
  });

  $(document).ready(function(){
    $('body').on("click",'.sendToRejectCheckBox', function() {
      var currentIndex = $(this).parent().index() + 1;
      var lastIndex = currentIndex + 7;
      
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

    $('body').on("click",'.receiveAsWellCheckBox', function() {
      
      if ($(this).is(':checked')) {
        $(this).siblings("input[type=hidden]").prop('disabled', true);
        $(this).siblings("input[type=hidden]").attr('disabled', true);
      } else {
        $(this).siblings("input[type=hidden]").prop('disabled', false);
        $(this).siblings("input[type=hidden]").attr('disabled', false);
      }
    });

    $('body').on("click",'.generateStockCard', function() {
      var date_in = ($(this).parent().parent().find('.datepicker')[0]).value;
      var product_id = $(this).attr('data-product-id');
      w = 600;
      h = 650;
      var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);

      window.open("{{ Url('') }}/job-order/receiving/stock-card-print/"+order_id+"/"+product_id+"?is_rejected=0&date_in=" + date_in, "Stock Card Print", "width="+w+", height="+h+", top="+top+", left="+left);

    });

    $('body').on("click",'.generateRejectStockCard', function() {
      var date_in = ($(this).parent().parent().find('.datepicker')[0]).value;
      var product_id = $(this).attr('data-product-id');
      w = 600;
      h = 650;
      var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);

      window.open("{{ Url('') }}/job-order/receiving/stock-card-print/"+order_id+"/"+product_id+"?is_rejected=1&date_in=" + date_in, "Stock Card Print", "width="+w+", height="+h+", top="+top+", left="+left);

    });

    $('body').on("click",'.generateBalanceStockCard', function() {
      var date_in = ($(this).parent().parent().find('.datepicker')[0]).value;
      var product_id = $(this).attr('data-product-id');
      w = 600;
      h = 650;
      var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);

      window.open("{{ Url('') }}/job-order/receiving/stock-card-print/"+order_id+"/"+product_id+"?is_rejected=0&is_balance=1&date_in=" + date_in, "Stock Card Print", "width="+w+", height="+h+", top="+top+", left="+left);

    });
    
    initDatePicker();
  });

  @if(!empty($jobOrder))
    $('.ajax-job-orders').trigger("change");
  @endif

  $('.combine_models_bom_checkbox').click(function() {
    combine_models();
  });

  function addTableData(){
    console.log("retert")
    $.ajax({
      url: window.location.origin+'/job-order/receiving/table-data',
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

  function combine_models() {
    var isChecked = $('.combine_models_bom_checkbox').is(':checked');
    
    if(isChecked) {
      $('.combine_models_bom_hidden').val('1');
      var totalQty = 0;
      $.each(jobProductsList, function( index, jobProduct ) {
        totalQty += jobProduct.quantity;
      });
      combineAllQuantities(totalQty);
    } else {
      $('.combine_models_bom_hidden').val('0');
      combineAllQuantities(jobProductsList[0].quantity);
    }
  }

  function combineAllQuantities(totalQty) {
    // var inputsOfQty = $("input[name='total_quantity[]']");
    // $.each(inputsOfQty, function( index, inputQty ) {
    //   $(inputQty).val(totalQty);
    // });
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
  $('#multi-product-table thead td').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
 
    // DataTable

  $('body').on("change",'.qtyRecInput', function() {
    var recQty = $(this).val();
    var orderQty = $(this).parent().parent().find('.orderQty').val();
    $(this).parent().parent().find('.qtyBalInput').val(orderQty - recQty);
  });
</script>
@endsection