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
                  <i class="fas fa-pallet"></i> Issue Item for Production <a
                    href="{{ route('inventory.list.for.production') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a>
                </h4>
              </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('inventory.store.issue.for.production') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        @csrf

                                        <div class="form-group">
                                          <label for="stock_card_id">Stock Card <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#scanQRCodeStockCardModal"> <i class="fas fa-qrcode"></i> Click here to Scan Stock Card</button></label>
                                          <select class="select2AutoFocus form-select stock_cards" id="stock_card_id" name="stock_card_id" required>
                                            <option></option>
                                            @foreach ($stockCards as $stockCard)
                                            <option data-qrcode="{{ $stockCard->stock_card_number }}" value="{{ $stockCard->id }}" @if ($stockCard->id == old('stock_card_id')) selected @endif>{{ $stockCard->id }}
                                              ({{ $stockCard->stock_card_number }})</option>
                                            @endforeach
                                          </select>
                                        </div>

                                        <div class="form-group">
                                          <label for="quantity">Quantity <span class="required">*</span></label>
                                          <input type="text" class="form-control" name="quantity" value="1" required>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label for="site_id">Site <span class="required">*</span></label>
                                            <select class="select2AutoFocus form-select multi_sites" id="site_id" name="site_id" required>
                                              <option></option>
                                              @foreach ($multiSites as $site)
                                              <option value="{{ $site->id }}" @if ($site->id == old('site_id')) selected @endif>{{ $site->name }}
                                                ({{ $site->code }})</option>
                                              @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                          <label for="site_location_id">Location <span class="required">*</span></label>
                                          <select class="select2AutoFocus form-select locations" id="site_location_id" name="site_location_id" required>
                                            <option></option>
                                            @foreach ($siteLocations as $location)
                                            <option value="{{ $location->id }}" @if ($location->id == old('site_location_id')) selected @endif>{{ $location->name }}
                                              ({{ $location->code }})</option>
                                            @endforeach
                                          </select>
                                        </div>

                                        <div class="form-group">
                                          <label for="department_id">Department <span class="required">*</span></label>
                                          <select class="select2AutoFocus form-select departments" id="department_id" name="department_id" required>
                                            <option></option>
                                            @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @if ($department->id == old('department_id')) selected @endif>{{ $department->name }}
                                              ({{ $department->code }})</option>
                                            @endforeach
                                          </select>
                                        </div>

                                        <div class="form-group">
                                          <label for="machine_id">Machine <span class="required">*</span></label>
                                          <select class="select2AutoFocus form-select machines" id="machine_id" name="machine_id" required>
                                            <option></option>
                                            @foreach ($machines as $machine)
                                            <option value="{{ $machine->id }}" @if ($machine->id == old('machine_id')) selected @endif>{{ $machine->name }}
                                              ({{ $machine->code }})</option>
                                            @endforeach
                                          </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                      <div class="alert alert-info d-none stockCardDetailsBox">
                                        <h3>Stock Card Details</h3>
                                        <div class="detailsList">
                                        </div>
                                      </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Add</button>
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

<!-- Modal 1 -->
<div class="modal fade" id="scanQRCodeStockCardModal" tabindex="-1"
  aria-labelledby="scanQRCodeStockCardModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanQRCodeStockCardModalLabel">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Choose Camera</h5>
        <select class="form-select mb-2" id="camera-select"></select>
        <h5>Cam Preview</h5>
        <canvas id="QRCodeStockCardPlugin" style="width: 100%"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
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

  $('.multi_sites').select2({
      "language": {
        "noResults": function() {
          return "No Results Found... <a href='{{ route('multi-sites.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-plus'></i> Add New Item</a>";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Search Sites',
      minimumInputLength: 1
    });

  $('.locations').select2({
      "language": {
        "noResults": function() {
          return "No Results Found... <a href='{{ route('site-locations.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-plus'></i> Add New Item</a>";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Search Locations',
      minimumInputLength: 1
    });

  $('.departments').select2({
      "language": {
        "noResults": function() {
          return "No Results Found... <a href='{{ route('departments.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-plus'></i> Add New Item</a>";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Search Departments',
      minimumInputLength: 1
    });

  $('.machines').select2({
      "language": {
        "noResults": function() {
          return "No Results Found... <a href='{{ route('machines.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-plus'></i> Add New Item</a>";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Search Machines',
      minimumInputLength: 1
    });

  $('#stock_card_id').change(function() {
    var stockCardId = $(this).val();
    var routeUrl = "{{ route('stock-cards.get.id')}}?id="+stockCardId;

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        $('.stockCardDetailsBox').removeClass('d-none');

        var htmlList = `
          <ul class="list-unstyled">
            <li><b>QTY Ordered</b>: ${response.ordered_quantity ?? 'N/A'}</li>
            <li><b>QTY Available on Stock Card</b>: ${response.available_quantity ?? 'N/A'}</li>
            <li><b>Total QTY Available in Stock</b>: ${response.stock.quantity ?? 'N/A'}</li>
          </ul>
        `;
        $('.detailsList').html(htmlList);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });
  });
</script>

<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/qrcodelib.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/webcodecamjs.js"></script>
<script type="text/javascript">
  // QR Code Scan 1
  var QRCodeStockCardModal = document.getElementById('scanQRCodeStockCardModal');
  var QRCodeStockCardPlugin = null;
  QRCodeStockCardModal.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 1);
        }
    };
    QRCodeStockCardPlugin = new WebCodeCamJS("canvas#QRCodeStockCardPlugin").buildSelectMenu("#camera-select", "environment|back").init(QRCodeArgs);
    QRCodeStockCardPlugin.play();
  });
  document.querySelector("#camera-select").addEventListener("change", function() {
    if (QRCodeStockCardPlugin.isInitialized()) {
      QRCodeStockCardPlugin.stop().play();
    }
  });
  QRCodeStockCardModal.addEventListener('hidden.bs.modal', function (event) {
    QRCodeStockCardPlugin.stop();
    QRCodeStockCardPlugin = null;
  });

  function searchAndInitQRCode(QRCode, modalNo) {
    if(modalNo == 1) {
      $('#stock_card_id').find('option[data-qrcode="'+QRCode+'"]').attr('selected', 'selected');
      
      $('#stock_card_id').select2({
        width: '100%',
        placeholder: 'Search Stock Card Code',
      });

      $('#scanQRCodeStockCardModal').modal('hide');
    }
  }
</script>
@endsection
