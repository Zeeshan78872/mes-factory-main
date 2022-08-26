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
            <h1 class="m-0 text-dark"><i class="fas fa-dolly"></i> Manage Shipping</h1>
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
                  <h4 class="card-title">Create New Shipment Order
                  <a href="{{ route('shippings.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('shippings.store') }}">
                                <div class="row">
                                    <div class="col-md-5">
                                        @csrf

                                        <h4>Shipping Details</h4>
                                        <hr>

                                        <div class="form-group">
                                          <label for="load_to">Load To <span class="required">*</span></label>
                                          <select class="form-select" id="load_to" name="load_to" required>
                                            <option value="1" @if($loadToType == 1) selected @endif>Contena</option>
                                            <option value="2" @if($loadToType == 2) selected @endif>Lorry</option>
                                          </select>
                                        </div>
                                       
                                        <div class="form-group">
                                          <label for="order_id">Job Order <span class="required">*</span></label>
                                          <select class="select2AutoFocus ajax-job-orders form-select" id="order_id" name="order_id" required>
                                            <option></option>
                                          </select>
                                        </div>

                                        <div class="containerFieldsBox @if($loadToType == 2) d-none @endif">
                                          <div class="form-group">
                                            <label for="truck_out_date">Truck out Date</label>
                                            <input type="text" class="form-control datepicker" id="truck_out_date" name="truck_out_date" value="{{ old('truck_out_date') }}">
                                          </div>
                                          <div class="form-group">
                                            <label for="qc_date">QC Date</label>
                                            <input type="text" class="form-control datepicker" id="qc_date" name="qc_date" value="{{ old('qc_date') }}">
                                          </div>
                                          <div class="form-group">
                                            <label for="booking_no">Booking No</label>
                                            <input type="text" class="form-control" id="booking_no" name="booking_no" value="{{ old('booking_no') }}">
                                          </div>
                                          <div class="form-group">
                                            <label for="container_no">Container No</label>
                                            <input type="text" class="form-control" id="container_no" name="container_no" value="{{ old('container_no') }}">
                                          </div>
                                          <div class="form-group">
                                            <label for="seal_no">Seal No</label>
                                            <input type="text" class="form-control" id="seal_no" name="seal_no" value="{{ old('seal_no') }}">
                                          </div>
                                        </div>

                                        <div class="lorryFieldsBox @if($loadToType == 1) d-none @endif">
                                          <div class="form-group">
                                            <label for="vehicle_no">Vehicle No</label>
                                            <input type="text" class="form-control" id="vehicle_no" name="vehicle_no"
                                              value="{{ old('vehicle_no') }}">
                                          </div>
                                          <div class="form-group">
                                            <label for="company">Company</label>
                                            <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
                                          </div>
                                          <div class="form-group">
                                            <label for="do_no">DO No</label>
                                            <input type="text" class="form-control" id="do_no" name="do_no" value="{{ old('do_no') }}">
                                          </div>

                                          <div class="form-group">
                                            <label for="stock_cards">Stock Cards</label>
                                            <select class="select2AutoFocus form-select stock_cards" id="stock_cards" name="stock_cards[]" multiple>
                                              <option></option>
                                            </select>
                                          </div>
                                        </div>

                                        <div class="form-group">
                                          <label for="description">Description/Remarks</label>
                                          <textarea class="form-control" id="description" name="description" maxlength="250">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-7 containerLeftOverBox @if($loadToType == 2) d-none @endif">
                                      <h4>Left Over:</h4>
                                      <hr>

                                      <div class="table-responsive leftOverList">
                                        <table class="table table-striped table-hover table-bordered align-middle">
                                          <thead>
                                            <tr>
                                              <th scope="col">PO</th>
                                              <th scope="col">MODEL/PART/COLOR</th>
                                              <th scope="col">QTY</th>
                                              <th scope="col">ACTION</th>
                                            </tr>
                                          </thead>
                                      
                                          <tbody>
                                            <tr>
                                              <td>
                                                <input type="text" class="form-control" id="leftover_po_no" name="leftover_po_no[]">
                                              </td>
                                              <td>
                                                <select class="select2AutoFocus ajax-products" class="form-select" name="leftover_model_product[]">
                                                  <option></option>
                                                </select>
                                              </td>
                                              <td>
                                                <input type="number" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" id="leftover_qty" name="leftover_qty[]">
                                              </td>
                                              <td>
                                                -
                                              </td>
                                            </tr>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td colspan="5">
                                                <button type="button" class="btn btn-sm btn-secondary addMoreLeftOver"><i class="fas fa-plus"></i> Add More</button>
                                              </td>
                                            </tr>
                                          </tfoot>
                                        </table>
                                      </div>

                                      <h4 class="mt-3">Replacement Part:</h4>
                                      <hr>
                                      <div class="table-responsive replacementPartsList">
                                        <table class="table table-striped table-hover table-bordered align-middle">
                                          <thead>
                                            <tr>
                                              <th scope="col">MODEL/PART/COLOR</th>
                                              <th scope="col">QTY</th>
                                              <th scope="col">ACTION</th>
                                            </tr>
                                          </thead>
                                      
                                          <tbody>
                                            <tr>
                                              <td>
                                                <select class="select2AutoFocus ajax-products" class="form-select" name="replace_model_product[]">
                                                  <option></option>
                                                </select>
                                              </td>
                                              <td>
                                                <input type="number" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" id="replace_qty" name="replace_qty[]">
                                              </td>
                                              <td>
                                                -
                                              </td>
                                            </tr>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td colspan="5">
                                                <button type="button" class="btn btn-sm btn-secondary addMoreReplacement"><i class="fas fa-plus"></i> Add More</button>
                                              </td>
                                            </tr>
                                          </tfoot>
                                        </table>
                                      </div>

                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Create Shipment Order</button>
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
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.js"></script>
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
  // Ajax Select2 Job Orders
  var ajaxJobOrdersOptions = {
      "language": {
        "noResults": function() {
          return "No Results Found...";
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
      placeholder: 'Search Product/Model/Part',
      minimumInputLength: 1,
      allowClear: true,
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

  $('.datepicker').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: false,
    locale: {
      format: 'YYYY-MM-DD',
      cancelLabel: 'Clear'
    }
  }, function(start, end, label) {
  });

  $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });

  $('.datepicker').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });

  $("#load_to").change( function() {
    $(".containerFieldsBox, .lorryFieldsBox, .containerLeftOverBox").toggleClass('d-none');
  });

  var ajaxStockCardsOptions = {
      "language": {
        "noResults": function() {
          return "No Results Found...";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Scan/Search Stock Cards',
      minimumInputLength: 1,
      ajax: {
        url: '{{ route('stock-cards.ajax.search') }}',
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
  $('.stock_cards').select2(ajaxStockCardsOptions);

  // Left Overs Add more
  $(".addMoreLeftOver").click(function () {
    var trBody = `<tr>
                    <td>
                      <input type="text" class="form-control" id="leftover_po_no" name="leftover_po_no[]">
                    </td>
                    <td>
                      <select class="select2AutoFocus ajax-products" class="form-select" name="leftover_model_product[]">
                        <option></option>
                      </select>
                    </td>
                    <td>
                      <input type="number" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" id="leftover_qty" name="leftover_qty[]">
                    </td>
                    <td>
                      <a href="javascript:void(0)" class="btn btn-sm btn-danger removeLeftOverBtn"><i class="far fa-trash-alt"></i></a>
                    </td>
                  </tr>`;
    $(".leftOverList tbody").append(trBody);
    $('.ajax-products').select2(ajaxProductsOptions);
  });
  
  // Left Overs Remove btn
  $(document).on('click', '.removeLeftOverBtn', function() {
    if(confirm('Are you sure?')) {
      $(this).parent().parent().remove();
    }
  });

  // Replacement Add more
  $(".addMoreReplacement").click(function () {
    var trBody = `<tr>
                    <td>
                      <select class="select2AutoFocus ajax-products" class="form-select" name="replace_model_product[]">
                        <option></option>
                      </select>
                    </td>
                    <td>
                      <input type="number" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" id="replace_qty" name="replace_qty[]">
                    </td>
                    <td>
                      <a href="javascript:void(0)" class="btn btn-sm btn-danger removeReplacementBtn"><i class="far fa-trash-alt"></i></a>
                    </td>
                  </tr>`;
    $(".replacementPartsList tbody").append(trBody);
    $('.ajax-products').select2(ajaxProductsOptions);
  });

  // Replacement Remove btn
  $(document).on('click', '.removeReplacementBtn', function() {
    if(confirm('Are you sure?')) {
      $(this).parent().parent().remove();
    }
  });

</script>
@endsection