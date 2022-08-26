@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header d-print-none">
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
                <div class="card-header d-print-none">
                  <h4 class="card-title">View - Shipping#{{ $shipping->id }}
                    <a href="{{ route('shippings.index') }}" class="btn btn-sm btn-primary float-end ms-4"><i class="fas fa-chevron-left"></i> Go Back</a>
                    <button onclick="window.print();" type="button" class="btn btn-sm btn-warning float-end "><i class="fas fa-print"></i> Print</button>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <h2 class="d-none d-print-block">Loading List</h2>
                  <div class="row">
                      <div class="col-md-12">
                          <form>
                              <div class="row">
                                  <div class="col-md-12 containerBox @if($shipping->load_to == 2) d-none @endif"">

                                    <div class="row">

                                      <div class="col-md-6 col-print-6">
                                        <ul class="list-unstyled">
                                          <li><b>LOAD TO:</b> {{ $shipping->load_to == 1 ? 'Contena' : 'Lorry' }}</li>
                                          <li><b>JOB ORDER NO:</b> {{ $shipping->jobOrder->order_no_manual }}</li>
                                          <li><b>TRUCK OUT DATE:</b> {{ $shipping->truck_out_date?? "-" }}</li>
                                        </ul>
                                      </div>
                                      <div class="col-md-6 col-print-6">
                                        <ul class="list-unstyled">
                                          <li><b>QC DATE:</b> {{ $shipping->qc_date?? "-" }}</li>
                                          <li><b>BOOKING NO:</b> {{ $shipping->booking_no?? "-" }}</li>
                                          <li><b>CONTAINER NO:</b> {{ $shipping->container_no?? "-" }}</li>
                                          <li><b>SEAL NO:</b> {{ $shipping->seal_no?? "-" }}</li>
                                        </ul>
                                      </div>

                                      <div class="col-md-12">
                                        <b>Description/Remarks:</b> {{ $shipping->description?? "-" }}
                                      </div>

                                      <div class="col-md-12">
                                        <div class="table-responsive containerList">
                                          <table class="table table-striped table-hover table-bordered align-middle">
                                            <thead>
                                              <tr>
                                                <th scope="col">QR CODE</th>
                                                <th scope="col">MODEL</th>
                                                <th scope="col">COLOR</th>
                                                <th scope="col">CTN</th>
                                                <th scope="col">Additional CTN from previous PO</th>
                                                <th scope="col">Total Planned CTN</th>
                                                <th scope="col">Total Actual CTN</th>
                                                <th scope="col">Total Leftover CTN</th>
                                                <th scope="col">Reason Leftover</th>
                                              </tr>
                                            </thead>
                                        
                                            <tbody>
                                              @foreach($shipping->items as $item)
                                              <tr>
                                                <td class="text-center">
                                                  {!! QrCode::size(80)->generate($item->qr_code); !!}
                                                  <br>
                                                  {{ $item->qr_code }}
                                                </td>
                                                <td>{{ $item->product->model_name }} ({{ $item->product->product_name}})</td>
                                                <td>{{ $item->product->color_name}} ({{ $item->product->color_code}})</td>
                                                <td>{{ $shipping->jobOrder->container_vol }}</td>
                                                <td></td>
                                                <td>{{ $item->total_plan_qty }}</td>
                                                <td>{{ $item->actual_loaded_qty }}</td>
                                                <td>
                                                  @if($item->actual_loaded_qty !== null)
                                                  {{ $item->total_plan_qty - $item->actual_loaded_qty }}
                                                  @endif
                                                </td>
                                                <td></td>
                                              </tr>
                                              @endforeach
                                            </tbody>
                                          </table>
                                        </div>

                                      </div>
                                      
                                    </div>
                                  </div>

                                  <div class="col-md-12 lorryBox @if($shipping->load_to == 1) d-none @endif"">

                                    <div class="row">
                                    
                                      <div class="col-md-6 col-print-6">
                                        <ul class="list-unstyled">
                                          <li><b>LOAD TO:</b> {{ $shipping->load_to == 1 ? 'Contena' : 'Lorry' }}</li>
                                          <li><b>VEHICLE NO:</b> {{ $shipping->vehicle_no ?? "-" }}</li>
                                          <li><b>COMPANY:</b> {{ $shipping->company ?? "-" }}</li>
                                        </ul>
                                      </div>
                                      <div class="col-md-6 col-print-6">
                                        <ul class="list-unstyled">
                                          <li><b>JOB ORDER NO:</b> {{ $shipping->jobOrder->order_no_manual }}</li>
                                          <li><b>DO:</b> {{ $shipping->do_no ?? "-" }}</li>
                                          <li><b>DATE:</b> {{ $shipping->created_at ?? "-" }}</li>
                                        </ul>
                                      </div>

                                      <div class="col-md-12">
                                        <b>Description/Remarks:</b> {{ $shipping->description?? "-" }}
                                      </div>
                                    
                                      <div class="col-md-12">
                                        <div class="table-responsive containerList">
                                          <table class="table table-striped table-hover table-bordered align-middle">
                                            <thead>
                                              <tr>
                                                <th scope="col">QR CODE</th>
                                                <th scope="col">MODEL</th>
                                                <th scope="col">COLOR</th>
                                                <th scope="col">Total Planned CTN</th>
                                                <th scope="col">Total Actual CTN</th>
                                              </tr>
                                            </thead>
                                    
                                            <tbody>
                                              @foreach($shipping->items as $item)
                                              <tr>
                                                <td class="text-center">
                                                  {!! QrCode::size(80)->generate($item->qr_code); !!}
                                                  <br>
                                                  {{ $item->qr_code }}
                                                </td>
                                                <td>{{ $item->product->model_name }} ({{ $item->product->product_name}})</td>
                                                <td>{{ $item->product->color_name}} ({{ $item->product->color_code}})</td>
                                                <td>{{ $item->total_plan_qty }}</td>
                                                <td>{{ $item->actual_loaded_qty }}</td>
                                              </tr>
                                              @endforeach
                                            </tbody>
                                          </table>
                                        </div>
                                    
                                      </div>
                                    
                                    </div>
                                  
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