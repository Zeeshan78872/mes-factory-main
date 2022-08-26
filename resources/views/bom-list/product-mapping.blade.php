@extends('layouts.app')

@section('custom_css')
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-project-diagram"></i> Manage Product BOM Mapping</h1>
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
                  <h4 class="card-title">Create BOM List for Product # {{ $product->id }} - {{ $product->model_name }}
                  <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <!-- <div class="form-group">
                          <label for="">Search Items:</label>
                          <select class="ajax-products" class="form-select">
                            <option></option>
                          </select>
                        </div> -->
                        <div class="form-group mt-1">
                          <button data-bs-toggle="modal" data-bs-target="#addMultipleProducts" id="AddItemsInTable_" class="btn btn-sm btn-primary" style="display: none;">Add Multiple Items</button>
                        </div>

                        <hr>
                        <h4>BOM List:</h4>
                        <p>
                          Upload CSV/Excel and overwrite BOM: <input type="file" id="bomUploadFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                          <br>
                          <a href="{{ url('/assets/sample_bom_import.xlsx') }}" target="_blank">
                            <span class="badge bg-info text-dark">Download Sample File</span>
                          </a>
                        </p>

                        <hr>

                        <form method="post" action="{{ route('products.bom.mapping.update', $product->id) }}">
                          @csrf

                          <div class="table-responsive BOMProductsList">
                            <table class="table table-striped table-hover table-bordered align-middle">
                              <thead>
                                <tr>
                                  <th scope="col">Item</th>
                                  <th scope="col" style="min-width: 150px;">Part Number</th>
                                  <th scope="col">Material</th>
                                  <th scope="col" style="min-width: 150px;">Length & Unit</th>
                                  <th scope="col" style="min-width: 150px;">Width & Unit</th>
                                  <th scope="col" style="min-width: 150px;">Thick & Unit</th>
                                  <th scope="col" style="min-width: 150px;">QTY</th>
                                  <th scope="col" style="min-width: 86px;">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                            </table>
                          </div>

                          <div class="mt-2">
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
  var oid;
  var pid;
  // Upload BOM List
  $('#bomUploadFile').change(function () {

    if(confirm('Are you sure? it will overwrite below BOM Mapping')) {
      var formData = new FormData();
      formData.append('file', $('#bomUploadFile')[0].files[0]);
      formData.append('product_id', {{ $product->id }});

      var routeUrl = "{{ route('products.bom.mapping.upload.file.update', ['id'=> ':product_id']) }}";
      routeUrl = routeUrl.replace(':product_id', {{ $product->id }});

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: routeUrl,
        type: "post",
        data : formData,
        processData: false,
        contentType: false,
        success: function (response) {
          $('#bomUploadFile').val('');
          location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.status);
          if(jqXHR.status == 422) {
            var alertMsg = "Error: " + jqXHR.status + " Message: " + jqXHR.responseJSON.errors[0];
          } else {
            var alertMsg = "Error: " + jqXHR.status + " Message: " + jqXHR.responseJSON;
          }
          alert(alertMsg);
          $('#bomUploadFile').val('');
        }
      });
    } else {
      $('#bomUploadFile').val('');
    }
    
  });

  var jobProductsList;
  var combineQtyForCalculation=0;
  var unitsHtml = '';
  var unitsArr = [];
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
      width: '50%',
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

  function loadBomItems() {
    // Get Job Order BOM List Items
    var routeUrl = "{{ route('products.bom.mapping', ['id'=> ':product_id']) }}";
    routeUrl = routeUrl.replace(':product_id', {{ $product->id }});
    pid = <?= $product->id ?>;

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        var lastCat = 0;
        var lastParentProductId = 0;
        var bomListHtmlData = '';
        $('.BOMProductsList tbody').html('');
        $("#AddItemsInTable_").show();
        addTableData();
        $.each(response, function( index, bomItem ) {
            var productData = bomItem.item;
            // Heading
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

            var bomQty = bomItem.quantity;

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
                      <td colspan="4" class="fs-6 text-white bg-secondary"></td>
                      <td class="fs-6 text-white bg-secondary">
                          <input type="number" class="form-control" name="quantity[]" value="${findParentQty(response, productData.parent_product.id)}" required >
                      </td>
                      <td colspan="3" class="bg-secondary">
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
                          </div>
                      </td>
                  </tr>`;
            }

            if(productData.category_id !== null) {
              // Items
              if(productData.category.has_bom_items == "1") {
                isBomCat= true;
                // BOM Category Heading
                bomListHeadingHtmlData += `
                <tr data-bom-cat-id="${productData.category_id}" class="rowHeading">
                    <td colspan="8" class="fs-5 text-white text-center bg-info">
                    <b>${productData.category.name}</b>
                    </td>
                </tr>`;
              }
            }

            if(productData.parent_id !== null || isBomCat) {
              bomListHtmlData += `
              <tr>
                <td>
                  <input type="hidden" class="form-control" name="item_id[]" value="${productData.id}">
                  ${productData.model_name ?? "N/A"}
                </td>
                <td>${productData.product_name ?? "N/A"}</td>
                <td>${productData.material ?? "N/A"}</td>
                <td>
                  <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-sm lengthInput" name="length[]"
                      value="${bomItem.length ?? productData.length}">
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
                    <input type="text" class="form-control form-control-sm widthInput" name="width[]"
                      value="${bomItem.width ?? productData.width}">
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
                    <input type="text" class="form-control form-control-sm thickInput" name="thick[]"
                      value="${bomItem.thick ?? productData.thick}">
                    <span class="input-group-text">
                      <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
                        <option></option>
                        ${currentThickUnitHtml}
                      </select>
                    </span>
                  </div>
                </td>
                <td>
                  <input type="number" class="form-control" name="quantity[]" value="${bomQty}" required>
                </td>
                <td>
                  <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${productData.id}"><i
                      class="far fa-eye"></i></a>
                  <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
                </td>
              </tr>
              `;
            }

            if(isBomCat) {
                var isHeadingExists = checkIfCategoryExists(productData.category_id);
            } else {
                var isHeadingExists = checkIfParentProductExists(productData.parent_id);
            }

            var htmlBody = '';

            // Add new Heading Row
            if(isHeadingExists === false) {
                htmlBody += bomListHeadingHtmlData;
                htmlBody += bomListHtmlData;
                $('.BOMProductsList tbody').append(htmlBody); 
            } else {
              htmlBody += bomListHtmlData;
              $(".BOMProductsList tbody tr:nth-child(" + (isHeadingExists+1) + ")").after(htmlBody);
            }

        });

      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });
  }

  function findParentQty(bomItems, parentId) {
    var index = bomItems.findIndex(function(item) {
      return item.item_id == parentId
    });
    if(index >= 0) {
      return bomItems[index].quantity;
    }
    return '';
  }

  // Load BOM Items
  $(document).ready(function() {
    loadBomItems();
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
              <td colspan="4" class="fs-6 text-white bg-secondary"></td>
              <td class="fs-6 text-white bg-secondary">
                  <input type="number" class="form-control" name="quantity[]" value="1" required >
              </td>
              <td colspan="3" class="bg-secondary">
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
                  </div>
              </td>
          </tr>`;
    }

    if(productData.category_id !== null) {
      // Items
      if(productData.category.has_bom_items == "1") {
        isBomCat= true;
        // BOM Category Heading
        bomListHeadingHtmlData += `
        <tr data-bom-cat-id="${productData.category_id}" class="rowHeading">
            <td colspan="8" class="fs-5 text-white text-center bg-info">
              <b>${productData.category.name}</b>
            </td>
        </tr>`;
      }
    }

    bomListHtmlData += `
    <tr>
      <td>
        <input type="hidden" class="form-control" name="item_id[]" value="${productData.id}">
        ${productData.model_name ?? "N/A"}
      </td>
      <td>${productData.product_name ?? "N/A"}</td>
      <td>${productData.material ?? "N/A"}</td>
      <td>
        <div class="input-group input-group-sm">
          <input type="text" class="form-control form-control-sm lengthInput" name="length[]"
            value="${productData.length ?? ''}">
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
          <input type="text" class="form-control form-control-sm widthInput" name="width[]"
            value="${productData.width ?? ''}">
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
          <input type="text" class="form-control form-control-sm thickInput" name="thick[]"
            value="${productData.thick ?? ''}">
          <span class="input-group-text">
            <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
              <option></option>
              ${currentThickUnitHtml}
            </select>
          </span>
        </div>
      </td>
      <td>
        <input type="number" class="form-control" name="quantity[]" value="1" required>
      </td>
      <td>
        <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn" data-id="${productData.id}"><i
            class="far fa-eye"></i></a>
        <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
      </td>
    </tr>
    `;

    if(isBomCat && productData.category_id === null) {
      alert('invalid product selected that dont have any category');
      return;
    }
    if(!isBomCat && productData.parent_id === null) {
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
    if(isHeadingExists === false) {
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