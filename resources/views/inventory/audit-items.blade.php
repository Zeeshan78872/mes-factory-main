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
                    <i class="fas fa-cash-register"></i> Audit Items
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <h4>Select Item for Audit:</h4>

                  <!-- <div class="row">
                    <div class="col-md-6">
                      <label for="products">Product </label>
                      <select class="form-select" id="products" name="products" data-type="product">
                        <option></option>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">[{{ $product->model_name }}] {{ $product->product_name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-md-6">
                      <label for="stockCards">Stock Card </label>
                      <select class="form-select" id="stockCards" name="stockCards" data-type="stockCard">
                        <option></option>
                        @foreach ($stockCards as $stockCard)
                        <option value="{{ $stockCard->id }}">{{ $stockCard->stock_card_number }}</option>
                        @endforeach
                      </select>
                    </div>
                  
                    <div class="col-md-4">
                      <label for="category_id">Category </label>
                      <select class="form-select category_id" name="category_id" data-type="category" data-category-type="category_id">
                        <option></option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  
                    <div class="col-md-4">
                      <label for="category_id">Sub Category </label>
                      <select class="form-select category_id" name="category_id" data-type="category" data-category-type="subcategory_id">
                        <option></option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  
                    <div class="col-md-4">
                      <label for="category_id">BOM Category </label>
                      <select class="form-select category_id" name="category_id" data-type="category" data-category-type="bomcategory_id">
                        <option></option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  
                  </div> -->
                  <table id="multi-product-table" class="table table-bordered table-striped" style="width:100%;">
        
                 
                 
                <thead>
                        <tr>
                            <th><input onclick="$('#multi-product-table tbody input[type=checkbox]').prop('checked',this.checked)" type="checkbox" id="selectAll"></th>
                            <th>Product No</th>
                            <th>Item</th>
                            <th>Color Name</th>
                            <th>Color Code</th>
                            <th>Stock Card</th>
                        </tr>
                    </thead>
                    <tbody id="stock-card-products">
                    </tbody>
                </table>
                <button id="AddItemsInTable" class="btn btn-success" onclick="getProductsDetails()">Add Items</button>

                  <div class="row mt-5">
                    <div class="col-md-12">
                      <div class="auditItems">
                        <h4>Audit List:</h4>
                        <form method="post" action="{{ route('inventory.updateAuditItems') }}">
                          @csrf
                        <div class="table-responsive auditItemsList">
                          <table class="table table-striped table-hover table-bordered align-middle">
                            <thead>
                              <tr>
                                <th scope="col">Model Name</th>
                                <th scope="col">Stock Card</th>
                                <th scope="col">Color Name</th>
                                <th scope="col">Color Code</th>
                                <th scope="col">Length & Unit</th>
                                <th scope="col">Width & Unit</th>
                                <th scope="col">Thick & Unit</th>
                                <th scope="col">Current Balance</th>
                                <th scope="col">In</th>
                                <th scope="col">Out</th>
                              </tr>
                            </thead>
                        
                            <tbody>
                              <tr>
                                <td colspan="9" class="text-center"><h3 class="p-5">Select Item First</h3></td>
                              </tr>
                            </tbody>
                          </table>

                          <div class="mt-2">
                            <button type="submit" class="btn btn-primary mt-2 d-none submitBtn float-end"><i class="fas fa-save"></i> Update Inventory Stock</button>
                          </div>
                        </div>

                        </form>
                      </div>
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
<link href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
@section('custom_js')
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(()=>{
    showProducts();
  })
$('select').select2({
      "language": {
        "noResults": function() {
          return "No Results Found...";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Search...',
      minimumInputLength: 1
    });

function getProductsDetails() {
  var selected = [];
      var tableData = "";
      $('#multi-product-table tbody input[type=checkbox]:checked').each(function(a,b){
          selected.push($(b).val())
      })


    var routeUrl = "{{ route('inventory.getProductsInventory') }}?stockCardId=" + selected;

  // Ajax products
  $.ajax({
    url: routeUrl,
    type: "get",
    success: function (response) {
      var products = response;
      generateAuditList(products);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
    }
  });
}

function generateAuditList(items) {
  $(".auditItemsList tbody").html('');
  var tableBodyHtml = '';
  if(items.length == 0) {
    tableBodyHtml += `
      <tr>
        <td colspan="9" class="text-center"><h3 class="p-5">No Products Found</h3></td>
      </tr>
    `;
  }
  $(items).each(function(index, item) {
    tableBodyHtml += `
      <tr>
        <td>
          <input type="hidden" name="product_id[]" value="${item.pid}"> 
          <input type="hidden" name="ps_id[]" value="${item.ps_id}"> 
          ${item.model_name}
        </td>
        <td>
          <input type="hidden" name="sc_id[]" value="${item.sc_id}"> 
          ${item.stock_card_number}
        </td>
        <td>${item.color_name ?? '-'}</td>
        <td>${item.color_code ?? '-'}</td>
        <td>${item.length_unit  ?? ''}</td>
        <td>${item.width_unit  ?? ''}</td>
        <td>${item.thick_unit  ?? ''}</td>
        <td>${item.quantity ?? '0'}</td>
        <td><input type="text" class="form-control form-control-sm" name="tally_in[]" value="0" required></td>
        <td><input type="text" class="form-control form-control-sm" name="tally_out[]" value="0" required></td>
      </tr>
    `;
  });
  $(".auditItemsList tbody").html(tableBodyHtml);

  if(items.length == 0) {
    $('.submitBtn').addClass('d-none');
  } else {
    $('.submitBtn').removeClass('d-none');
  }

}

function showProducts(){
        
        var tableData;
                        $.ajax({
            url: "{{ route('productions.stock-card') }}",
            type: "get",
            success: (response) => {
                console.log(response);
                var jsonData = JSON.parse(response);
                for(var i = 0;i < jsonData.length;i++){
                    tableData += `<tr>
                    <td><input value="${jsonData[i].id}" type="checkbox"></td>
                            <td>
                                ${jsonData[i].product_name}
                                </td>
                                <td>
                                ${jsonData[i].model_name}
                                </td>
                                <td>
                                ${jsonData[i].color_name}
                                </td>
                                <td>
                                ${jsonData[i].color_code}
                                </td>
                            <td>
                                ${jsonData[i].stock_card_number}
                            </td>
                        </tr>`
                }
                $("#stock-card-products").html(tableData);
                $("#addMultipleProducts").modal('toggle');
                // if(table){
                //     table.destroy();
                // }
        table = $('#multi-product-table').DataTable({
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
            },
            error: (jqXHR, textStatus, errorThrown) => {
                "Error: " + jqXHR.status;
            }
        })       
    }
</script>
@endsection