@extends('layouts.app')

@section('custom_css')
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" type="text/css" href="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.css" />
<style>
  .table-scrollable {
  overflow-x: auto;
  /* max-width: 600px; */
  box-shadow: inset 0 0 5px rgba(150, 150 ,150,0.35);
  margin: auto;
}
input.form-control {
  width: auto;
}
body{
  overflow-x: hidden;
}
</style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-clipboard-list"></i> Manage Job Orders</h1>
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
                  <h4 class="card-title">Edit - Job Order#{{ $jobOrder->id }} - Manual Order #{{ $jobOrder->order_no_manual }}
                  <a href="{{ route('job-orders.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a>
                  <input type="hidden" name="" id="job_order" value="{{ $jobOrder->id }}">
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('job-orders.update', $jobOrder->id) }}" enctype="multipart/form-data">
                                <div class="row">
                                  <div class="col-md-4">
                                    @csrf
                                    {{ method_field('PUT') }}

                                    <div class="form-group">
                                      <label for="site_id">Site/Location <span class="required">*</span></label>
                                      <select class="form-select form-control" id="site_id" name="site_id" required>
                                        @foreach ($multiSites as $site)
                                        <option value="{{ $site->id }}" @if ($site->id == $jobOrder->site_id) selected @endif>{{ $site->name }}
                                          ({{ $site->code }})</option>
                                        @endforeach
                                      </select>
                                    </div>

                                    <div class="form-group">
                                      <label for="customer_id">Customer <span class="required">*</span></label>
                                      <select class="ajax-customers form-control" id="customer_id" name="customer_id" required>
                                        <option value="{{ old('customer_id', $jobOrder->customer_id) }}" selected>
                                          {{ $jobOrder->customer->customer_name. " " . $jobOrder->customer->country_name . " [" . $jobOrder->customer->country_code . "] " }}
                                        </option>
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <label for="order_no_manual">Manual Order No</label>
                                      <input type="text" class="form-control" id="order_no_manual" name="order_no_manual"
                                        value="{{ old('order_no_manual', $jobOrder->order_no_manual) }}">
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="qc_date">QC Date</label>

                                      <input class="form-check-input qc_date_checkbox ms-2 " type="checkbox" name="same_qc_date" value="1" @if(!empty($jobOrder->qc_date)) checked @endif> Same for each Product

                                      <input type="text" style="width: 100%;" class="form-control datepicker" id="qc_date" name="qc_date" value="{{ old('qc_date', $jobOrder->qc_date) }}" @if(empty($jobOrder->qc_date)) disabled @endif>
                                    </div>

                                    <div class="form-group">
                                      <label for="crd_date">Estimate Delivery Date</label>

                                      <input class="form-check-input crd_date_checkbox ms-2 " type="checkbox" name="same_crd_date" value="1" @if(!empty($jobOrder->crd_date)) checked @endif> Same for each Product

                                      <input type="text" style="width: 100%;" class="form-control datepicker" id="crd_date" name="crd_date" value="{{ old('crd_date', $jobOrder->crd_date) }}" @if(empty($jobOrder->crd_date)) disabled @endif>
                                    </div>
                                    <div class="form-group">
                                      <label for="crd_date">Truck in </label>


                                      <input type="text" style="width: 100%;" class="form-control datepicker" id="truck_in" name="truck_in" value="{{ old('truck_in', $jobOrder->truck_in) }}" @if(empty($jobOrder->truck_in)) disabled @endif>
                                    </div>
                                  </div>

                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="po_no">PO No</label>

                                      <input class="form-check-input po_no_checkbox ms-2 "  type="checkbox" name="same_po_no" value="1" @if(!empty($jobOrder->po_no)) checked @endif> Same for each Product

                                      <input type="text" style="width: 100%;" class="form-control" id="po_no" name="po_no" value="{{ old('po_no', $jobOrder->po_no) }}"  @if(empty($jobOrder->po_no)) disabled @endif>
                                    </div>

                                    <div class="form-group">
                                      <label for="container_vol">Container Volume</label>

                                      <input class="form-check-input container_vol_checkbox ms-2 "  type="checkbox" name="same_container_vol" value="1" @if(!empty($jobOrder->container_vol)) checked @endif> Same for each Product

                                      <input type="text" style="width: 100%;" class="form-control" id="container_vol" name="container_vol" value="{{ old('container_vol', $jobOrder->container_vol) }}" @if(empty($jobOrder->container_vol)) disabled @endif>
                                    </div>
                                  </div>

                                  <div class="col-md-12 mt-2">
                                    <h4>Products</h4>
                                    <div class="form-group">

                                      <div class="table-responsive productsList">
                                        <table class="m-auto table table-striped table-hover  table-striped table-hover table-bordered align-middle">
                                          <thead>
                                            <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">Product</th>
                                              <th scope="col">Product Picture</th>
                                              <th scope="col" style="width: 140px;">quantity</th>
                                              <th scope="col">Product Test</th>
                                              <th scope="col">Remarks</th>
                                              <th scope="col" @if(!empty($jobOrder->qc_date)) style="display: none;" @endif>QC Date</th>
                                              <th scope="col" @if(!empty($jobOrder->crd_date)) style="display: none;" @endif>Estimate Delivery Date</th>
                                              <th scope="col" @if(!empty($jobOrder->truck_in)) style="display: none;" @endif>Truck In</th>
                                              <th scope="col" @if(!empty($jobOrder->po_no)) style="display: none;" @endif>PO NO</th>
                                              <th scope="col" @if(!empty($jobOrder->container_vol)) style="display: none;" @endif>Container Vol.</th>
                                              <th scope="col" style="max-width: 400px">Packing</th>
                                              <th scope="col">Action</th>
                                            </tr>
                                          </thead>

                                          <tbody>
                                            @foreach($jobOrder->jobProducts as $jobProduct)
                                            <tr>
                                              <td>
                                                #{{ $loop->iteration }}
                                                <input type="hidden" name="job_order_product_ids[]" value="{{ $jobProduct->id }}">
                                              </td>
                                              <td>
                                                <select class="ajax-products" class="form-select" name="product_ids[]" required>
                                                  <option></option>
                                                  <option value="{{ $jobProduct->product_id }}" selected>{{ $jobProduct->product->model_name }}
                                                    {{ $jobProduct->product->color_name }} {{ $jobProduct->product->color_code }}</option>
                                                </select>
                                              </td>
                                              <td>
                                                  <img src="url('{{ Url('').'/uploads/'.$product->image }}')" width="50px" height="50px" />
                                              </td>
                                              <td>
                                                <input type="number" class="form-control" name="quantity[]" value="{{ $jobProduct->quantity }}" required>
                                              </td>
                                              <td>
                                                <input class="form-check-input product_test" type="checkbox" name="product_test[]" value="1" @if($jobProduct->product_test) checked @endif>

                                                <div class="remarks @if(!$jobProduct->product_test) d-none @endif mt-2">
                                                  Test Remarks
                                                  <textarea style="width: 190px;" class="form-control" name="product_test_remarks[]" rows="3" cols="4">{{ $jobProduct->product_test_remarks }}</textarea>
                                                </div>

                                              </td>
                                              <td>
                                                <input class="form-check-input product_remarks" type="checkbox" value="1" @if(!empty($jobProduct->remarks)) checked @endif>

                                                <div class="remarks @if(empty($jobProduct->remarks)) d-none @endif mt-2">
                                                  Remarks
                                                  <textarea style="width: 190px;" class="form-control" name="product_remarks[]" rows="3">{{ $jobProduct->remarks }}</textarea>
                                                </div>

                                              </td>

                                              <td @if(!empty($jobOrder->qc_date)) style="display: none;" @endif>
                                                <input type="text" class="form-control datepicker" name="product_qc_date[]" value="{{ $jobProduct->qc_date }}">
                                              </td>
                                              <td @if(!empty($jobOrder->crd_date)) style="display: none;" @endif>
                                                <input type="text" class="form-control datepicker" name="product_crd_date[]" value="{{ $jobProduct->crd_date }}">
                                              </td>
                                              <td @if(!empty($jobOrder->truck_in)) style="display: none;" @endif>
                                                <input type="text" class="form-control datepicker" name="product_truck_in[]" value="{{ $jobProduct->truck_in }}">
                                              </td>
                                              <td @if(!empty($jobOrder->po_no)) style="display: none;" @endif>
                                                <input type="text" class="form-control" name="product_po_no[]" value="{{ $jobProduct->po_no }}">
                                              </td>
                                              <td @if(!empty($jobOrder->container_vol)) style="display: none;" @endif>
                                                <input type="text" class="form-control" name="product_container_vol[]" value="{{ $jobProduct->container_vol }}">
                                              </td>
                                              <td style="max-width: 400px">
                                                <div class="row">
                                                  <div class="col-md-12">
                                                    Select Packing:
                                                  </div>
                                                  <div class="col-md-6">
                                                    <select class="select2AutoFocus ajax-job-orders form-select">
                                                      <option></option>
                                                    </select>
                                                  </div>
                                                  <div class="col-md-6">
                                                    <select class="job-orders-products form-select form-select-sm ajax-products-packing">
                                                      <option>Select Job Order First</option>
                                                    </select>
                                                  </div>
                                                </div>
                                                <br><br>

                                                <textarea class="form-control product_packing_details editor{{ $loop->index }}" name="product_packing[]" rows="3" placeholder="packing details">{{ $jobProduct->product_packing }}</textarea>

                                                <label for="packing_imgs">Packing Images</label>

                                                <input type="file" class="form-control" id="packing_imgs" name="packing_imgs[{{$loop->index}}][]" accept="image/png, image/gif, image/jpeg" multiple>

                                                <ul class="list-inline mt-2">
                                                  @foreach($jobProduct->packingPictures as $picture)
                                                  <li class="list-inline-item text-center picture_{{ $picture->id }}">
                                                    <a href="{{ Url('') }}/uploads/{{ $picture->picture_link }}" target="_blank">
                                                      <img src="{{ Url('') }}/uploads/{{ $picture->picture_link }}" alt="picture" class="img-thumbnail">
                                                    </a>
                                                    <br>
                                                    <a href="javascript:void(0)" class="text-danger" onclick="removePicture('{{ $picture->id }}')">Remove</a>
                                                  </li>
                                                  @endforeach
                                                </ul>
                                                  <div class="oldPackingPictures">
                                                  </div>
                                                  <input type="hidden" name="packing_id[]" id="old_packing_id" value="{{ $jobProduct->product->packing->id ?? ''}}">
                                                  <input type="hidden" name="old_packing_pictures[]" id="old_packing_pictures">
                                                  <div class="overwrite_packing_box">
                                                    <input class="form-check-input overwrite_packing_checkbox ms-2" type="checkbox" name="overwrite_packing[0]" value="1"> Overwrite Packing
                                                  </div>
                                              </td>

                                              <td>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn"><i class="far fa-eye"></i></a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
                                              </td>
                                            </tr>
                                            @endforeach
                                          </tbody>

                                          <tfoot>
                                            <tr>
                                              <td colspan="5">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary addMoreProducts"><i class="fas fa-cube"></i>
                                                  Add More</a>
                                              </td>
                                            </tr>
                                          </tfoot>

                                        </table>
                                      </div>

                                    </div>
                                  </div>

                                  <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Update</button>
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

<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{ route('customers.store') }}" id="customerForm">
          <div class="row">
            <div class="col-md-4">
              @csrf
              <div class="form-group">
                <label for="customer_name">Customer Name <span class="required">*</span></label>
                <input type="text" class="form-control" id="customer_name" name="customer_name"
                  value="{{ old('customer_name') }}" required>
              </div>

              <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name"
                  value="{{ old('company_name') }}">
              </div>

              <div class="form-group">
                <label for="country_name">Country Name</label>
                <input type="text" class="form-control" id="country_name" name="country_name"
                  value="{{ old('country_name') }}">
              </div>

              <div class="form-group">
                <label for="country_code">Country Code</label>
                <input type="text" class="form-control" id="country_code" name="country_code"
                  value="{{ old('country_code') }}">
              </div>

            </div>

            <div class="col-md-4">

              <label for="emails">Email IDs</label>
              <div class="emailsBox">
                <div class="form-group">
                  <input type="email" class="form-control" name="emails[]">
                </div>
              </div>

              <button type="button" class="addEmails btn btn-primary btn-sm float-end mt-2">
                <i class="far fa-envelope"></i> Add More Email ID
              </button>

            </div>

            <div class="col-md-4">

              <label for="phoneNos">Phone Numbers</label>
              <div class="phoneNosBox">
                <div class="form-group">
                  <input type="text" class="form-control" name="phoneNos[]">
                </div>
              </div>

              <button type="button" class="addPhoneNos btn btn-primary btn-sm float-end mt-2">
                <i class="fas fa-phone-alt"></i> Add More Phone Number
              </button>

            </div>

            <div id="errorsResp" class="mt-2"></div>

            <div class="col-md-12">
              <button type="submit" class="btn btn-primary mt-2 btn-sm"><i class="fas fa-plus"></i> Add</button>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('custom_js')
<script src="{{ url('/assets') }}/custom.js"></script>
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/daterange-picker/daterangepicker.js"></script>
<script src="{{ url('/assets') }}/plugins/ckeditor/ckeditor.js"></script>

<script>
  // CKeditor Config
  var ckEditorConfig = {
    toolbar: {
      items: [
        'heading',
        '|',
        'specialCharacters',
        'fontBackgroundColor',
        'fontColor',
        'fontSize',
        'highlight',
        'underline',
        'subscript',
        'superscript',
        'bold',
        'italic',
        'alignment',
        'bulletedList',
        'numberedList',
        '|',
        'outdent',
        'indent',
        '|',
        'undo',
        'redo',
        'findAndReplace',
        'blockQuote',
      ]
    },
    language: 'en',
    licenseKey: ''
  };

  var ckEditors = [];

  function createCkEditor(id='') {
    // Ckeditor For Description
    ClassicEditor.create( document.querySelector( '.editor'+id ), ckEditorConfig )
      .then( editor => {
        window.editor = editor;
        ckEditors.push(editor);
      })
      .catch( error => {
        console.error( 'Oops, something went wrong!' );
        console.error( error );
      });
  }

  @foreach($jobOrder->jobProducts as $jobProduct)
  createCkEditor({{ $loop->index }});
  @endforeach

  function initDatePicker() {
    $('.datepicker').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      autoUpdateInput: false,
      locale: {
        format: 'YYYY-MM-DD'
      }
    }, function(start, end, label) {
      // console.log(start.format('YYYY-MM-DD'));
    });
  }

  initDatePicker();

  $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });

  //
  // $('.select_ptoduct')on('change',function(){
  //   console.log($('.select_ptoduct').val());
  // });
  // Ajax Select2 Customers
  $('.ajax-customers').select2({
    "language": {
      "noResults": function() {
        return "No Results Found... <a href='#' data-bs-toggle='modal' data-bs-target='#customerModal' class='btn btn-primary btn-sm' target='_blank'><i class='fas fa-users'></i> Add New Customer</a>";
      }
    },
    escapeMarkup: function (markup) {
      return markup;
    },
    width: '100%',
    placeholder: 'Search Customer',
    minimumInputLength: 1,
    ajax: {
      url: '{{ route('customers.search') }}',
      dataType: 'json',
      delay: 800,
      processResults: function (response) {
        return {
          results: response
        };
      },
      cache: true
    }
  });

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
          // console.log(response)
          return {
            results: response
          };
        },
        cache: true
      }
    };
  $('.ajax-products').select2(ajaxProductsOptions);


  // Create Customer Form
  $("#customerForm").submit( function(event) {
    $("#errorsResp").html('');
    event.preventDefault();
    /* Get from elements values */
    var values = $(this).serialize();

    $.ajax({
      url: $("#customerForm").attr('action'),
      type: "post",
      data: values,
      success: function (response) {
        var customerData = {
          id: response.id,
          text: response.customer_name + " " + response.country_name + " [" + response.country_code + "]"
        };
        var newOption = new Option(customerData.text, customerData.id, true, true);
        $('.ajax-customers').append(newOption).trigger('change');
        $('#customerForm').trigger("reset");

        $('#customerModal').modal('hide');

        alert('Customer Added successfully.');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if(jqXHR.status == 422) {
          var respBody = jqXHR.responseJSON;
          var respBody = jqXHR.responseJSON;

          errorsHtml = '<div class="alert alert-danger"><ul>';

          $.each( respBody.errors, function( key, value ) {
            errorsHtml += '<li>' + value[0] + '</li>';
          });
          errorsHtml += '</ul></div>';
          $("#errorsResp").html(errorsHtml);
        }
      }
    });

  });

  // Close Select2 when modal is opened
  var customerModal = document.getElementById('customerModal');
  customerModal.addEventListener('show.bs.modal', function (event) {
    $('.ajax-customers').select2("close");
  })

  // Customer Form Validations
  // Add Email IDs
  $(".addEmails").click(function(e) {
    e.preventDefault();
    $(".emailsBox").append('<div class="form-group mt-2"><input type="text" class="form-control" name="emails[]"><a href="#" class="remove_email_field text-danger">Remove Email ID</a></div>');
  });

  $(".emailsBox").on("click",".remove_email_field", function(e) {
    e.preventDefault();
    $(this).parent('div').remove();
  });

  // Add Phone Numbers
  $(".addPhoneNos").click(function(e) {
    e.preventDefault();
    $(".phoneNosBox").append('<div class="form-group mt-2"><input type="text" class="form-control" name="phoneNos[]"><a href="#" class="remove_phone_field text-danger">Remove Phone No</a></div>');
  });

  $(".phoneNosBox").on("click",".remove_phone_field", function(e) {
    e.preventDefault();
    $(this).parent('div').remove();
  });

  // Show/Hide Product Test Remarks
  $(document).on('click', '.product_test', function() {
    if ($(this).prop("checked")) {
      $(this).next().removeClass('d-none');
    } else {
      $(this).next().addClass('d-none');
      $(this).next().children().val('');
    }
  });

  // Show/Hide Product Remarks
  $(document).on('click', '.product_remarks', function() {
    if ($(this).prop("checked")) {
      $(this).next().removeClass('d-none');
    } else {
      $(this).next().addClass('d-none');
      $(this).next().children().val('');
    }
  });

  // Show/Hide Table Column
  $(document).on('click', '.qc_date_checkbox', function() {
    if ($(this).prop("checked")) {
      $('.productsList table tr > *:nth-child(7)').hide();
      $(this).next().prop('disabled', false);
    } else {
      $('.productsList table tr > *:nth-child(7)').show();
      $(this).next().prop('disabled', true);
    }
  });

  $(document).on('click', '.crd_date_checkbox', function() {
    if ($(this).prop("checked")) {
      $('.productsList table tr > *:nth-child(8)').hide();
      $('.productsList table tr > *:nth-child(9)').hide();
      $(this).next().prop('disabled', false);
      $("#truck_in").prop('disabled', false);
    } else {
      $('.productsList table tr > *:nth-child(8)').show();
      $('.productsList table tr > *:nth-child(9)').show();
      $(this).next().prop('disabled', true);
      $("#truck_in").prop('disabled', true);
    }
  });
  $(document).on('click', '.container_vol_checkbox', function() {
    if ($(this).prop("checked")) {
      $('.productsList table tr > *:nth-child(11)').hide();
      $(this).next().prop('disabled', false);
    } else {
      $('.productsList table tr > *:nth-child(11)').show();
      $(this).next().prop('disabled', true);
    }
  });
  $(document).on('click', '.po_no_checkbox', function() {
    if ($(this).prop("checked")) {
      $('.productsList table tr > *:nth-child(10)').hide();
      $(this).next().prop('disabled', false);
    } else {
      $('.productsList table tr > *:nth-child(10)').show();
      $(this).next().prop('disabled', true);
    }
  });

  $(document).on('click', '.viewProductBtn', function() {
    var selectedOption = $(this).parent().parent().find("td:nth-child(2)").find('.ajax-products').val();
    if(selectedOption.length <= 0) {
      alert('Select a Product to view details');
    } else {
      var routeUrl = "{{ route('products.show', ':id')}}";
      routeUrl = routeUrl.replace(':id', selectedOption);

      window.open(routeUrl, '_blank');
    }
  });

  $(document).on('click', '.removeProductBtn', function() {
    if(confirm('Are you sure?')) {
      if($(this).parent().parent().find('input[type=hidden]').length > 0) {
        var jobOrderProductId = $(this).parent().parent().find('input[type=hidden]').val();
        callDeletItem(jobOrderProductId, 'job-orders/product', false);
      }
      $(this).parent().parent().remove();

      $(".productsList tbody").find("input[type=file]").each(function(index, field){
        $(this).attr('name', 'packing_imgs['+index+'][]');
      });
    }
  });
  $(document).on('change', '.ajax-products', function() {
    // console.log($(this).val());
    var productid = $(this).val();
    var routeUrl = "{{ route('products.show', ':id')}}";
    routeUrl = routeUrl.replace(':id', productid);
    $.ajax({
      type: "get",
        url: routeUrl,
        success: function (search) {
          console.log(search);
          var packingPictures = search.product_pictures;
        var pictureHtml = '<ul style="width: 190px;">';
              var oldPictureIds = '';
              $.each(packingPictures, function( index, packingPicture ) {
                oldPictureIds += `${ packingPicture.picture_link },`;
                pictureHtml += `
                  <li class="list-inline-item text-center picture_${ packingPicture.id }" data-url="${packingPicture.picture_link}">
                    <a href="{{ Url('') }}/uploads/${packingPicture.picture_link}" target="_blank">
                      <img style="width:100px;height:100px" src="{{ Url('') }}/uploads/${packingPicture.picture_link}" alt="picture" class="img-thumbnail">
                    </a>
                    <br>
                  </li>
                `;
              });
              pictureHtml += '</ul>';
    var rowCount = $('.productsList tbody tr').length;

           $('#pic'+rowCount).html(pictureHtml);

        },
        cache: true
      })

  });


  $(".addMoreProducts").click(function () {
    var rowCount = $('.productsList tbody tr').length;

    var displayQCDate = displayCRDDate = displayContainerVol = displayPONO = displayTruck_in='';

    if ($(".qc_date_checkbox").prop("checked")) {
      displayQCDate = 'display: none;';
    }
    if ($(".crd_date_checkbox").prop("checked")) {
      displayCRDDate = 'display: none;';
    }
    if ($(".container_vol_checkbox").prop("checked")) {
      displayContainerVol = 'display: none;';
    }
    if ($(".po_no_checkbox").prop("checked")) {
      displayPONO = 'display: none;';
    }
    if ($(".crd_date_checkbox").prop("checked")) {
      displayTruck_in = 'display: none;';
    }

    var trBody = `<tr>
                  <td>#${rowCount + 1}</td>
                  <td>
                    <select class="ajax-products " class="form-select" name="product_ids[]" required>
                      <option></option>
                    </select>
                  </td>
                  <td id="pic${rowCount + 1}">

                    </td>
                  <td>
                    <input type="number" class="form-control" name="quantity[]" required>
                  </td>
                  <td>
                    <input class="form-check-input product_test" type="checkbox" value="1" name="product_test[]">

                    <div class="remarks d-none mt-2">
                      Test Remarks
                      <textarea class="form-control" name="product_test_remarks[]" rows="3"></textarea>
                    </div>

                  </td>
                  <td>
                    <input class="form-check-input product_remarks" type="checkbox" value="1">

                    <div class="remarks d-none mt-2">
                      Remarks
                      <textarea class="form-control" name="product_remarks[]" rows="3"></textarea>
                    </div>

                  </td>
                  <td style="${displayQCDate}">
                    <input type="text" class="form-control datepicker" name="product_qc_date[]" value>
                  </td>
                  <td style="${displayCRDDate}">
                    <input type="text" class="form-control datepicker" name="product_crd_date[]">
                  </td>
                  <td style="${displayCRDDate}">
                    <input type="text" class="form-control datepicker" name="product_truck_in[]">
                  </td>
                  <td style="${displayPONO}">
                    <input type="text" class="form-control" name="product_po_no[]">
                  </td>
                  <td style="${displayContainerVol}">
                    <input type="text" class="form-control" name="product_container_vol[]">
                  </td>

                  <td style="max-width: 400px">
                    <div class="row">
                      <div class="col-md-12">
                        Select Packing:
                      </div>
                      <div class="col-md-6">
                        <select class="select2AutoFocus ajax-job-orders form-select">
                          <option></option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <select class="job-orders-products form-select form-select-sm ajax-products-packing">
                          <option>Select Job Order First</option>
                        </select>
                      </div>
                    </div>
                    <br><br>
                    <textarea class="form-control product_packing_details editor${rowCount}" name="product_packing[]" rows="3" placeholder="packing details"></textarea>

                    <label for="packing_imgs">Packing Images</label>

                    <input type="file" class="form-control" id="packing_imgs" name="packing_imgs[${rowCount}][]" accept="image/png, image/gif, image/jpeg" multiple>

                    <div class="oldPackingPictures">
                    </div>
                    <input type="hidden" name="packing_id[]" id="old_packing_id">
                    <input type="hidden" name="old_packing_pictures[]" id="old_packing_pictures">
                    <div class="overwrite_packing_box">
                      <input class="form-check-input overwrite_packing_checkbox ms-2" type="checkbox" name="overwrite_packing[${rowCount}]" value="1">
                      Overwrite Packing
                    </div>
                  </td>

                  <td>
                    <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn"><i class="far fa-eye"></i></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a>
                  </td>
                </tr>`;
    $(".productsList tbody").append(trBody);

    // Re-init Date Pickers
    initDatePicker();

    //init CK Editor
    createCkEditor(rowCount);

    //init products
    $('.ajax-products').select2(ajaxProductsOptions);

    // init packing
    $('.ajax-job-orders').select2(ajaxJobOrdersOptions);
  });

  $(document.body).delegate('.ajax-products-packing', 'change', function() {
    var productId = $(this).val();
    var orderId = $(this).parent().prev().find('select').val();

    var routeUrl = "{{ route('job.products.packing') }}?order_id=" + orderId + "&product_id=" + productId;

    var overwritePackingBox = $(this).parent().parent().parent().find('.overwrite_packing_box');
    var packingIdBox = $(this).parent().parent().parent().find('#old_packing_id');
    var textareaDetails = $(this).parent().parent().parent().find('textarea.product_packing_details');
    var packingPicturesBox = $(this).parent().parent().parent().find('div.oldPackingPictures');
    var classesList = $(textareaDetails).prop("classList");
    var newCkEditorClassName = classesList[(classesList.length-1)];
    var newCkEditorClassNameIndex = newCkEditorClassName.replace("editor", "");
    if(newCkEditorClassNameIndex == "") {
      newCkEditorClassNameIndex = 0;
    }

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        if(response.product_packing != null) {
          packingIdBox.val(response.id);
          var packingDetail = response.product_packing ?? '';
          textareaDetails.val(packingDetail);
          ckEditors[newCkEditorClassNameIndex].setData(packingDetail);

          if(response.packing_pictures) {
            var packingPictures = response.packing_pictures ?? null;

            if(packingPictures.length > 0) {
              var pictureHtml = '<ul>';
              var oldPictureIds = '';
              $.each(packingPictures, function( index, packingPicture ) {
                oldPictureIds += `${ packingPicture.picture_link },`;
                pictureHtml += `
                  <li class="list-inline-item text-center picture_${ packingPicture.id }" data-url="${packingPicture.picture_link}">
                    <a href="{{ Url('') }}/uploads/${packingPicture.picture_link}" target="_blank">
                      <img src="{{ Url('') }}/uploads/${packingPicture.picture_link}" alt="picture" class="img-thumbnail">
                    </a>
                    <br>
                    <a href="javascript:void(0)" class="text-danger" onclick="removePicture('${ packingPicture.id }')">Remove</a>
                  </li>
                `;
              });
              pictureHtml += '</ul>';
              packingPicturesBox.html(pictureHtml);

              $('#old_packing_pictures').val(oldPictureIds);
            } else {
              packingPicturesBox.html('');
            }
          }
        } else {
          $(overwritePackingBox).show();
          packingIdBox.val('');
          textareaDetails.val('');
          ckEditors[newCkEditorClassNameIndex].setData('');
          packingPicturesBox.html('');
          $('#old_packing_pictures').val('');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });
  });

  function removePicture(id) {
    if(confirm('Are you sure? it will permanently delete from job order product!')) {
      var routeUrl = "{{ route('job-orders.product.picture.delete', ':id')}}";
      routeUrl = routeUrl.replace(':id', id);

      $.get(routeUrl, function(data, status) {
        if(status == 'success') {
          $(".picture_" + id).remove();

          setTimeout(function() {
            var picturesLi = $('div.oldPackingPictures ul li');

            oldPictureIds = '';
            $.each(picturesLi, function( index, pictureLi ) {
              var pictureUrl = $(pictureLi).attr('data-url');
              oldPictureIds += `${ pictureUrl },`;
            });

            $('#old_packing_pictures').val(oldPictureIds);
          }, 500);
        } else {
          alert('Failed to delete');
        }
      });
    }
  }

  // Ajax Products for Job Orders
  $(document.body).delegate('.ajax-job-orders', 'change', function() {
    var jobOrderId = $(this).val();
    var routeUrl = "{{ route('job-orders.show', ':id')}}";
    var selectInput = $(this).parent().next().find('select');
    routeUrl = routeUrl.replace(':id', jobOrderId);

    $.ajax({
      url: routeUrl,
      type: "get",
      success: function (response) {
        var jobProductsOptions = "<option></option>";
        jobProductsList = response.job_products;
        currentJobOrderProducts = jobProductsList;
        $( jobProductsList ).each(function(index, jobProduct) {
          jobProductsOptions += "<option value='" + jobProduct.product_id + "'>" + jobProduct.product.model_name + " [ " + jobProduct.product.product_name + " ] " + jobProduct.product.length + "x" + jobProduct.product.width + "x" + jobProduct.product.thick + "</option>";
        });

        selectInput.html(jobProductsOptions)
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + jqXHR.status + " Message:" + jqXHR.responseJSON);
      }
    });
  });
</script>
@endsection
