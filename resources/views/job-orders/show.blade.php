@extends('layouts.app')
@section('custom_css')
<style>
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
    <div class="content-header d-print-none">
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
                <div class="card-header d-print-none">
                  <h4 class="card-title">View - Job Order#{{ $jobOrder->id }} - Manual Order #{{ $jobOrder->order_no_manual }}
                    <a href="{{ route('job-orders.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 d-print-none">

                          Created At: {{ $jobOrder->created_at }}
                          <br>
                          Created By: <a href="{{ route('users.show', $jobOrder->created_by) }}" target="_blank">{{ $jobOrder->createdBy->name }}</a>
                          <br>
                          Last Updated by : <a href="{{ route('users.show', $jobOrder->updated_by) }}" target="_blank">{{ $jobOrder->updatedBy->name }}</a>
                          <br>
                          Updated At: {{ $jobOrder->updated_at }}
                          <hr>

                          Site: <span class="badge bg-info">{{ $jobOrder->site->name }} ({{ $jobOrder->site->code }})</span>

                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <a href="{{ route('customers.show', $jobOrder->customer_id) }}" target="_blank">
                              {{ $jobOrder->customer->customer_name. " " . $jobOrder->customer->country_name . " [" . $jobOrder->customer->country_code . "] " }}
                            </a>
                          </div>

                          <div class="form-group">
                            <label for="order_no_manual">Manual Order No</label>
                            <input type="text" class="form-control" id="order_no_manual" name="order_no_manual"
                              value="{{ $jobOrder->order_no_manual }}" disabled>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="qc_date">QC Date</label>
                            
                            <input class="form-check-input qc_date_checkbox ms-2" type="checkbox" name="same_qc_date" value="1" @if(!empty($jobOrder->qc_date)) checked @endif disabled> Same for each Product
                            
                            <input type="text" style="width: 100%;" class="form-control" id="qc_date" name="qc_date"
                              value="{{ $jobOrder->qc_date }}" disabled>
                          </div>
                        
                          <div class="form-group">
                            <label for="crd_date">Estimate Delivery Date</label>
                            
                            <input class="form-check-input crd_date_checkbox ms-2" type="checkbox" name="same_crd_date" value="1" @if(!empty($jobOrder->crd_date)) checked @endif disabled> Same for each Product

                            <input type="text" style="width: 100%;" class="form-control" id="crd_date" name="crd_date"
                              value="{{ $jobOrder->crd_date }}" disabled>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="po_no">PO No</label>
                            
                            <input class="form-check-input po_no_checkbox ms-2" type="checkbox" name="same_po_no" value="1" @if(!empty($jobOrder->po_no)) checked @endif disabled> Same for each Product

                            <input type="text" style="width: 100%;" class="form-control" id="po_no" name="po_no" value="{{ $jobOrder->po_no }}" disabled>
                          </div>
                        
                          <div class="form-group">
                            <label for="container_vol">Container Volume</label>
                            
                            <input class="form-check-input container_vol_checkbox ms-2" type="checkbox" name="same_container_vol" value="1" @if(!empty($jobOrder->container_vol)) checked @endif disabled> Same for each Product

                            <input type="text" style="width: 100%;" class="form-control" id="container_vol" name="container_vol"
                              value="{{ $jobOrder->container_vol }}" disabled>
                          </div>
                          
                        </div>

                        <div class="col-md-12">
                          <h4>Products</h4>
                          <div class="form-group">
                        
                            <div class="table-responsive productsList">
                              <table class="table table-striped table-hover table-bordered align-middle">
                                <thead>
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Pictures</th>
                                    <th scope="col">quantity</th>
                                    <th scope="col">Product Test</th>
                                    <th scope="col" class="@if(!empty($jobOrder->qc_date)) d-none @endif">QC Date</th>
                                    <th scope="col" class="@if(!empty($jobOrder->crd_date)) d-none @endif">Estimate Delivery Date</th>
                                    <th scope="col" class="@if(!empty($jobOrder->truck_in)) d-none @endif">Truck In</th>
                                    <th scope="col" class="@if(!empty($jobOrder->container_vol)) d-none @endif">Container Vol.</th>
                                    <th scope="col" class="@if(!empty($jobOrder->po_no)) d-none @endif">PO NO</th>
                                    <th scope="col">Remarks</th>
                                    <th scope="col">Packing</th>
                                    <th class="d-print-none"></th>
                                  </tr>
                                </thead>
                        
                                <tbody>
                                  @if(empty(count($jobOrder->jobProducts)))
                                  <tr>
                                    <td colspan="4" class="text-center"><b>No Products Added</b></td>
                                  </tr>
                                  @endif
                                  @foreach($jobOrder->jobProducts as $jobProduct)
                                  <tr>
                                    <td>#{{ $loop->iteration }}</td>
                                    <td>
                                      {{ $jobProduct->product->model_name }} {{ $jobProduct->product->color_name }} {{ $jobProduct->product->color_code }}
                                    </td>
                                    <td>
                                      <ul style="width: 190px;" class="list-inline mt-2">
                                        @foreach($jobProduct->product->productPictures as $picture)
                                        <li class="list-inline-item text-center picture_{{ $picture->id }}">
                                          <a href="{{ Url('') }}/uploads/{{ $picture->picture_link }}" target="_blank">
                                            <img src="{{ Url('') }}/uploads/{{ $picture->picture_link }}" alt="picture" class="img-thumbnail"
                                              style="width: 100px; height: 100px;">
                                          </a>
                                        </li>
                                        @endforeach
                                      </ul>
                                    </td>
                                    <td>
                                      {{ $jobProduct->quantity }}
                                    </td>
                                    <td>
                                      <input class="form-check-input product_test" type="checkbox" value="1" @if($jobProduct->product_test) checked @endif disabled>
                        
                                      <div class="remarks @if(!$jobProduct->product_test) d-none @endif mt-2">
                                        Test Remarks: <b>{{ $jobProduct->product_test_remarks }}</b>
                                      </div>
                        
                                    </td>

                                    <td @if(!empty($jobOrder->qc_date)) style="display: none;" @endif>
                                      <input type="text" class="form-control" value="{{ $jobProduct->qc_date }}" disabled>
                                    </td>
                                    <td @if(!empty($jobOrder->crd_date)) style="display: none;" @endif>
                                      <input type="text" class="form-control" value="{{ $jobProduct->crd_date }}" disabled>
                                    </td>
                                    <td @if(!empty($jobOrder->truck_in)) style="display: none;" @endif>
                                      <input type="text" class="form-control" value="{{ $jobProduct->truck_in }}" disabled>
                                    </td>
                                    <td @if(!empty($jobOrder->container_vol)) style="display: none;" @endif>
                                      <input type="text" class="form-control" value="{{ $jobProduct->container_vol }}" disabled>
                                    </td>
                                    <td @if(!empty($jobOrder->po_no)) style="display: none;" @endif>
                                      <input type="text" class="form-control" value="{{ $jobProduct->po_no }}" disabled>
                                    </td>
                                    
                                    <td>
                                      <input class="form-check-input product_remarks" type="checkbox" value="1" @if(!empty($jobProduct->remarks)) checked @endif disabled>
                        
                                      <div class="remarks @if(empty($jobProduct->remarks)) d-none @endif mt-2">
                                        Remarks: <b>{{ $jobProduct->remarks }}</b>
                                      </div>
                        
                                    </td>

                                    <td>
                                      {!! $jobProduct->product_packing !!}
                                      
                                      <label for="packing_imgs">Packing Images</label>
                                      <ul class="list-inline mt-2">
                                        @foreach($jobProduct->packingPictures as $picture)
                                        <li class="list-inline-item text-center picture_{{ $picture->id }}">
                                          <a href="{{ Url('') }}/uploads/{{ $picture->picture_link }}" target="_blank">
                                            <img src="{{ Url('') }}/uploads/{{ $picture->picture_link }}" alt="picture" class="img-thumbnail">
                                          </a>
                                        </li>
                                        @endforeach
                                      </ul>
                                    </td>
                                    <td class="d-print-none">
                                      <a href="javascript:void(0)" class="btn btn-sm btn-secondary viewProductBtn"><i
                                          class="far fa-eye"></i></a>
                                    </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                        
                              </table>
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
@endsection

@section('custom_js')
<script>
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
</script>
@endsection