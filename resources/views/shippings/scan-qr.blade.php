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
                  <h4 class="card-title">Scan QR Code
                  <a href="{{ route('shippings.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-md-4">
                          
                          <ul class="nav nav-pills nav-fill mt-5" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#newscan" type="button" role="tab"
                                aria-controls="newscan" aria-selected="true">New QR CODE SCAN</button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="oldscan-tab" data-bs-toggle="tab" data-bs-target="#oldscan" type="button" role="tab"
                                aria-controls="oldscan" aria-selected="false">OLD QR CODE SCAN CONTINUE</button>
                            </li>
                          </ul>

                          <hr>

                          <div class="tab-content mt-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="newscan" role="tabpanel" aria-labelledby="newscan-tab">
                              <form method="post" action="{{ route('shippings.process.store') }}">
                                <div class="row">
                                  <div class="col-md-12">
                                    @csrf

                                    <div class="form-group">
                                      <label for="qr_code" class="mb-1">QR Code <span class="required">*</span> <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scanQRCodeModal"> <i class="fas fa-qrcode"></i> Click here to Scan</button></label>
                                      <select class="select2AutoFocus form-control" id="qr_code" name="qr_code" required></select>
                                    </div>
                              
                                    <div class="form-group mt-2">
                                      <label for="total_planned_qty">Total Planned QTY <span class="required">*</span></label>
                                      <input type="number" step="1" class="form-control" id="total_planned_qty" name="total_planned_qty" required>
                                    </div>
                              
                                    <div class="form-group mt-2">
                                      <label for="worker_id">Worker ID <span class="required">*</span></label>
                                      <select class="select2AutoFocus form-select workers" id="worker_id" name="worker_id" required>
                                        <option></option>
                                        @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}" @if ($worker->id == old('worker_id')) selected @endif>{{ $worker->id }} - {{
                                          $worker->name }}
                                        </option>
                                        @endforeach
                                      </select>
                                    </div>
                              
                                    <button type="submit" class="btn btn-primary mt-4"><i class="fas fa-stopwatch"></i> Start Loading Progress</button>
                                  </div>
                                </div>
                              
                              </form>
                            </div>
                            <div class="tab-pane fade" id="oldscan" role="tabpanel" aria-labelledby="oldscan-tab">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="qr_code2" class="mb-1">QR Code <span class="required">*</span> <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scanQRCodeModal2"> <i class="fas fa-qrcode"></i> Click here to Scan</button></label>
                                    <select class="select2AutoFocus form-control" id="qr_code2" name="qr_code2" required></select>
                                  </div>

                                  <div class="form-group mt-2">
                                    <label for="worker_id2">Worker ID <span class="required">*</span></label>
                                    <select class="select2AutoFocus form-select workers" id="worker_id2" name="worker_id2" required>
                                      <option></option>
                                      @foreach ($workers as $worker)
                                      <option value="{{ $worker->id }}" @if ($worker->id == old('worker_id2')) selected @endif>{{ $worker->id }} - {{
                                        $worker->name }}
                                      </option>
                                      @endforeach
                                    </select>
                                  </div>
                            
                                  <button type="button" class="btn btn-primary mt-4 searchShippingItems"><i class="fas fa-search"></i> Search Inprogress Loading</button>

                                  <hr>

                                  <div class="table-responsive shippingItemsList d-none mt-2">
                                    <table class="table table-striped table-hover table-bordered align-middle">
                                      <thead>
                                        <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Work Status</th>
                                          <th scope="col">Worker</th>
                                          <th scope="col">Total Planned QTY</th>
                                          <th scope="col">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
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

<!-- Modal 1 -->
<div class="modal fade" id="scanQRCodeModal" tabindex="-1" aria-labelledby="scanQRCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanQRCodeModalLabel">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Choose Camera</h5>
        <select class="form-select mb-2" id="camera-select"></select>
        <h5>Cam Preview</h5>
        <canvas id="QRCode" style="width: 100%"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Modal 2 -->
<div class="modal fade" id="scanQRCodeModal2" tabindex="-1" aria-labelledby="scanQRCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanQRCodeModalLabel">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Choose Camera</h5>
        <select class="form-select mb-2" id="camera-select2"></select>
        <h5>Cam Preview</h5>
        <canvas id="QRCode2" style="width: 100%"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/qrcodelib.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/webcodecamjs.js"></script>

<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
  var ajaxSelectQROptions = {
      "language": {
        "noResults": function() {
          return "No Results Found...";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Scan/Search QR Code',
      minimumInputLength: 1,
      ajax: {
        url: '{{ route('shippings.qr.search') }}',
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

  // QR Code Scan 1
  var QRCodeModal = document.getElementById('scanQRCodeModal');
  var QRCodePlugin = null;
  QRCodeModal.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 1);
        }
    };
    QRCodePlugin = new WebCodeCamJS("canvas#QRCode").buildSelectMenu("#camera-select", "environment|back").init(QRCodeArgs);
    QRCodePlugin.play();
  });
  document.querySelector("#camera-select").addEventListener("change", function() {
    if (QRCodePlugin.isInitialized()) {
      QRCodePlugin.stop().play();
    }
  });
  QRCodeModal.addEventListener('hidden.bs.modal', function (event) {
    QRCodePlugin.stop();
    QRCodePlugin = null;
  });

  // QR Code Scan 2
  var QRCodeModal2 = document.getElementById('scanQRCodeModal2');
  var QRCodePlugin2 = null;
  QRCodeModal2.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs2 = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 2);
        }
    };
    QRCodePlugin2 = new WebCodeCamJS("canvas#QRCode2").buildSelectMenu("#camera-select2", "environment|back").init(QRCodeArgs2);
    QRCodePlugin2.play();
  });
  document.querySelector("#camera-select").addEventListener("change", function() {
    if (QRCodePlugin2.isInitialized()) {
      QRCodePlugin2.stop().play();
    }
  });
  QRCodeModal2.addEventListener('hidden.bs.modal', function (event) {
    QRCodePlugin2.stop();
    QRCodePlugin2 = null;
  });

  function searchAndInitQRCode(QRCode, modalNo) {
    var routeUrl = "{{ route('shippings.qr.show', ':id')}}";
    routeUrl = routeUrl.replace(':id', QRCode);

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        var optionText = "Job Order: " + response.shipping.job_order.order_no_manual + " Product: " + response.product.model_name + " " + response.product.product_name;
        var optionid = response.id;

        if(modalNo == 1) {
          $('#qr_code').html('<option value="'+optionid+'" selected>'+optionText+'</option>');
          $('#qr_code').select2(ajaxSelectQROptions);
          $('#scanQRCodeModal').modal('hide');
        } else {
          $('#qr_code2').html('<option value="'+optionid+'" selected>'+optionText+'</option>');
          $('#qr_code2').select2(ajaxSelectQROptions);
          $('#scanQRCodeModal2').modal('hide');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });
  }

  // Ajax Select2
  $('#qr_code, #qr_code2').select2(ajaxSelectQROptions);

  $('#worker_id, #worker_id2').select2({
    width: '100%',
    placeholder: 'Search worker',
  });

  $(".searchShippingItems").click(function () {
    var qr_code = $("#qr_code2").val();
    var worker_id = $("#worker_id2").val();

    var htmlBody = '';
    $.ajax({
        url: "{{ route('shippings.process.search') }}",
        type: "get",
        data: {
          'worker_id': worker_id,
          'qr_code': qr_code
        },
        success: (response) => {
            var shippingItems = response;
            if(shippingItems.length > 0) {
                $.each(shippingItems, function( index, shippingItem ) {
                    htmlBody += `
                    <tr>
                        <td>#${ index + 1 }</td>
                        <td>In Progress</td>
                        <td>${ shippingItem.worker.name }</td>
                        <td>${ shippingItem.total_plan_qty }</td>
                        <td><a href="{{ Url('shippings/process') }}/${ shippingItem.id }">Continue Work</a></td>
                    </tr>
                    `;
                });
            } else {
                htmlBody = `
                <tr>
                    <td colspan="8" class="text-center">
                        <h3>No Shipping Items found with that criteria! Try searching again.</h3>
                    </td>
                </tr>
                `;
            }

            $(".shippingItemsList tbody").html(htmlBody);
            $(".shippingItemsList").removeClass('d-none');
        },
        error: (jqXHR, textStatus, errorThrown) => {
            "Error: " + jqXHR.status;
        }
    })

  });
</script>
@endsection