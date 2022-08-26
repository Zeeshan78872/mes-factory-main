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
            <h1 class="m-0 text-dark"><i class="fas fa-tractor"></i> Daily Production</h1>
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
                    <i class="far fa-building"></i> Choose your department
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  
                  <div class="row">
                    {{--  Daily Production Content: START --}}
                    <div class="col-md-6">
                      <div class="alert alert-warning text-center p-4">
                        <a href="javascript:void(0);" onclick="showDepartments()"><h2><i class="fas fa-chalkboard-teacher"></i> PRODUCTION</h2></a>
                      </div>

                      <div class="productionBox" style="display:none">

                        <form method="post" action="{{ route('production.daily.store') }}">
                          @csrf

                          <div class="productionDepartments">

                            <div class="row">
                              <div class="col-md-12">
                                <ul class="list-unstyled">
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="part_type" value="1">
                                        White Part
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="part_type" value="2">
                                        Finishing Part
                                      </label>
                                    </div>
                                  </li>
                                </ul>

                              </div>
                              <div class="col-md-6 white_part" style="display: none">
                                <h4>White Part</h4>
                                <ul class="list-unstyled">
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="2" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        CNC
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="3" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        CUTTING
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="4" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        DRILLING
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="5" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        BRUSH
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="7" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        SANDING B
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="9" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="1">
                                        ASSEMBLY B
                                      </label>
                                    </div>
                                  </li>
                                </ul>
                              </div>

                              <div class="col-md-6 finishing_part" style="display: none">
                                <h4>Finishing Part</h4>
                                <ul class="list-unstyled">
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="6" data-is-machine="0"
                                          data-is-chemical="0" data-is-assembly="0">
                                        SANDING A
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="11" data-is-machine="0" data-is-chemical="1"
                                          data-is-assembly="0">
                                        FILLER
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="12" data-is-machine="0" data-is-chemical="1"
                                          data-is-assembly="0">
                                        SPRAY
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="8" data-is-machine="0"
                                          data-is-chemical="0" data-is-assembly="1">
                                        ASSEMBLY A
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="department" value="10" data-is-machine="0"
                                          data-is-chemical="0" data-is-assembly="1">
                                        PACKING
                                      </label>
                                    </div>
                                  </li>
                                </ul>
                              
                              </div>

                            </div>

                            
                          </div>

                          <div class="workStatus d-none">
                            <div class="row">
                              <div class="col-md-4">
                                <div class="alert alert-primary text-center p-4">
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="radio" name="workStatus" value="1">
                                      NEW ITEM
                                    </label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="alert alert-danger text-center p-4">
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="radio" name="workStatus" value="2">
                                      REWORK ITEM
                                    </label>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="alert alert-warning text-center p-4">
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="radio" name="workStatus" value="3">
                                      INPROGRESS WORK
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="workOnCodes d-none">
                          
                            <div class="form-group">
                              <label for="machine_id">Stock Cards <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#scanQRCodeStockCardProductionModal1"> <i class="fas fa-qrcode"></i> Click here to Scan Stock Card</button></label>
                              <select class="select2AutoFocus form-select stock_cards" id="stock_card_ids" name="stock_card_ids[]" multiple required>
                                <option></option>
                                @foreach ($stockCards as $stockCard)
                                <option data-qrcode="{{ $stockCard->stock_card_number }}" value="{{ $stockCard->id }}" @if ($stockCard->id == old('stock_card_ids[]')) selected
                                  @endif>{{ $stockCard->id }}
                                  ({{ $stockCard->stock_card_number }}) - [{{ $stockCard->product->model_name }}] {{ $stockCard->product->product_name }} - [{{ $stockCard->product->color_name }}] {{ $stockCard->product->color_code }}</option>
                                @endforeach
                              </select>
                            </div>
                          
                            <div class="form-group machineGroup mt-2">
                              <label for="machine_id">Machine Code <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#scanQRCodeMachineCodeModal"> <i class="fas fa-qrcode"></i> Click here to Scan Machine Code</button></label>
                              <select class="select2AutoFocus form-select machines" id="machine_id" name="machine_ids[]" multiple>
                                <option></option>
                                @foreach ($machines as $machine)
                                <option value="{{ $machine->id }}" @if ($machine->id == old('machine_id')) selected @endif>{{ $machine->name }}
                                  ({{ $machine->code }})</option>
                                @endforeach
                              </select>
                            </div>
                          
                            <div class="form-group mt-2">
                              <label for="worker_ids">Workers ID Code <span class="required">*</span> <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#scanQRCodeWorkerCodeModal"> <i class="fas fa-qrcode"></i> Click here to Scan Worker Code</button></label>
                              <select class="select2AutoFocus form-select workers" id="worker_ids" name="worker_ids[]" multiple required>
                                <option></option>
                                @foreach ($workers as $worker)
                                <option value="{{ $worker->id }}" @if ($worker->id == old('worker_id')) selected @endif>{{ $worker->name }}
                                </option>
                                @endforeach
                              </select>
                            </div>


                            <div class="form-group mt-2">
                              <label for="total_plan_qty">Total Plan Qty<span class="required">*</span></label>
                              <input type="number" min="1" class="form-control" id="total_plan_qty" name="total_plan_qty">
                            </div>

                            <div class="form-group mt-2">
                              <label for="testing_speed">Testing Speed</label>
                              <input type="text" class="form-control" id="testing_speed" name="testing_speed">
                            </div>

                            <div class="form-group mt-2">
                              <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-play"></i> Start Production</button>
                            </div>
                          
                          </div>

                        </form>

                        <div class="searchWorkByCodes d-none">

                          <h4>Search In Progress Work by Any following Fields:</h4>
                        
                          <div class="form-group">
                            <label for="stock_card_id2">Stock Card Code <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#scanQRCodeStockCardProductionModal2"> <i class="fas fa-qrcode"></i> Click here to Scan Stock Card</button></label>
                            <select class="select2AutoFocus form-select stock_cards2" id="stock_card_id2" name="stock_card_id2">
                              <option></option>
                              @foreach ($stockCards as $stockCard)
                              <option data-qrcode="{{ $stockCard->stock_card_number }}" value="{{ $stockCard->id }}" @if ($stockCard->id == old('stock_card_id2')) selected
                                @endif>{{ $stockCard->id }}
                                ({{ $stockCard->stock_card_number }})</option>
                              @endforeach
                            </select>
                          </div>
                        
                          <div class="form-group">
                            <label for="machine_id2">Machine Code</label>
                            <select class="select2AutoFocus form-select machines2" id="machine_id2" name="machine_id2">
                              <option></option>
                              @foreach ($machines as $machine)
                              <option value="{{ $machine->id }}" @if ($machine->id == old('machine_id2')) selected @endif>{{ $machine->name }}
                                ({{ $machine->code }})</option>
                              @endforeach
                            </select>
                          </div>
                        
                          <div class="form-group">
                            <label for="worker_id2">Workers ID Code</label>
                            <select class="select2AutoFocus form-select workers2" id="worker_id2" name="worker_id2">
                              <option></option>
                              @foreach ($workers as $worker)
                              <option value="{{ $worker->id }}" @if ($worker->id == old('worker_id2')) selected @endif>{{ $worker->name }}
                              </option>
                              @endforeach
                            </select>
                          </div>
                        
                          <div class="form-group mt-2">
                            <button type="button" class="btn btn-primary mt-2 searchByCode"><i class="fas fa-search"></i> Search Production Work</button>
                          </div>

                          <div class="table-responsive productionList d-none mt-2">
                            <table class="table table-striped table-hover table-bordered align-middle">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Work Status</th>
                                  <th scope="col">Stock Card</th>
                                  <th scope="col">Total Qty Plan</th>
                                  <th scope="col">Department</th>
                                  <th scope="col">Workers</th>
                                  <th scope="col">Machines</th>
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
                    {{--  Daily Production Content: END --}}

                    {{--  QC Content: START --}}
                    <div class="col-md-3">
                      <div class="alert alert-info text-center p-4">
                        <a href="javascript:void(0);" onclick="showQC()">
                          <h2><i class="fas fa-tasks"></i> QC</h2>
                        </a>
                      </div>

                      <div class="qcFormBox" style="display: none">
                        <form method="post" action="{{ route('quality-assurance.store') }}">
                          @csrf

                          <ol type="a" class="qcList">
                            <li>
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="qc_type" value="1" onclick="showIQC()" checked>
                                  IQC : Incoming QC – for receive material
                                </label>
                              </div>
                            </li>

                            <li>
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="qc_type" value="2" onclick="showIPQC()">
                                  IPQC: In progress QC - during production
                                </label>
                              </div>
                            </li>

                            <li>
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="qc_type" value="3" onclick="showFQC()">
                                  FQC: Final QC – Final good
                                </label>
                              </div>
                            </li>

                            <li>
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="qc_type" value="4" onclick="showFinishedGoodsReport()">
                                  FGR: Finished Goods Report
                                </label>
                              </div>
                            </li>

                          </ol>

                          <hr>

                          <div class="IQCBox" id="QCFormBox">
                            <div class="IQCcategory">
                              <h4>Choose Category</h4>
                              <ul class="list-unstyled">
                                <li>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="radio" name="IQCcategory" value="1" checked>
                                      RAW MATERIAL
                                    </label>
                                  </div>
                                </li>
                                <li>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="radio" name="IQCcategory" value="2">
                                      HARDWARE
                                    </label>
                                  </div>
                                </li>
                                <li>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="radio" name="IQCcategory" value="3">
                                      POLYFORM
                                    </label>
                                  </div>
                                </li>
                                <li>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input class="form-check-input" type="radio" name="IQCcategory" value="4">
                                      CARTON
                                    </label>
                                  </div>
                                </li>
                              </ul>
                            </div>

                          </div>

                          <div class="IPQCBox" style="display: none" id="QCFormBox">
                            <div class="IPQCDepartments">
                              <h4>Choose Department</h4>
                              <div class="row">
                              <div class="col-md-12">
                                <ul class="list-unstyled">
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="part_type" value="1">
                                        White Part
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="part_type" value="2">
                                        Finishing Part
                                      </label>
                                    </div>
                                  </li>
                                </ul>

                              </div>
                              <div class="col-md-6 white_part" style="display: none">
                                <h4>White Part</h4>
                                <ul class="list-unstyled">
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="2" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        CNC
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="3" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        CUTTING
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="4" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        DRILLING
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="5" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        BRUSH
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="7" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="0">
                                        SANDING B
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="9" data-is-machine="1"
                                          data-is-chemical="0" data-is-assembly="1">
                                        ASSEMBLY B
                                      </label>
                                    </div>
                                  </li>
                                </ul>
                              </div>

                              <div class="col-md-6 finishing_part" style="display: none">
                                <h4>Finishing Part</h4>
                                <ul class="list-unstyled">
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="6" data-is-machine="0"
                                          data-is-chemical="0" data-is-assembly="0">
                                        SANDING A
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="11" data-is-machine="0" data-is-chemical="1"
                                          data-is-assembly="0">
                                        FILLER
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="12" data-is-machine="0" data-is-chemical="1"
                                          data-is-assembly="0">
                                        SPRAY
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="8" data-is-machine="0"
                                          data-is-chemical="0" data-is-assembly="1">
                                        ASSEMBLY A
                                      </label>
                                    </div>
                                  </li>
                                  <li>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="radio" name="IPQCdepartment" value="10" data-is-machine="0"
                                          data-is-chemical="0" data-is-assembly="1">
                                        PACKING
                                      </label>
                                    </div>
                                  </li>
                                </ul>
                              
                              </div>

                            </div>
                            </div>
                          </div>

                          <div class="FQCBox" style="display: none" id="QCFormBox">
                          </div>

                          <div class="form-group qcStockCard">
                            <label for="stock_card_id3">Stock Card Code <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#scanQRCodeStockCardProductionModal3"> <i class="fas fa-qrcode"></i> Click here to Scan Stock Card</button></label>
                            <select class="select2AutoFocus form-select stock_cards3" id="stock_card_id3" name="stock_card_id3">
                              <option></option>
                              @foreach ($stockCards as $stockCard)
                              <option data-qrcode="{{ $stockCard->stock_card_number }}" value="{{ $stockCard->id }}" @if ($stockCard->id == old('stock_card_id3')) selected
                                @endif>{{ $stockCard->id }}
                                ({{ $stockCard->stock_card_number }})</option>
                              @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">Start QC</button>
                          </div>

                        </form>

                      </div>
                    </div>
                    {{--  QC Content: END --}}

                    {{--  Shipping Content: START --}}
                    <div class="col-md-3">
                      <div class="alert alert-success text-center p-4">
                        <a href="javascript:void(0);" onclick="showShipping()">
                          <h2><i class="fas fa-dolly"></i> SHIPPING</h2>
                        </a>
                      </div>

                      <div class="shippingBox" style="display: none">

                        <ul class="shippingList list-unstyled">
                          <li>
                            <a href="{{ route('shippings.create') }}?load_to_type=1" class="btn btn-sm btn-secondary mt-2">
                              <i class="fas fa-ship"></i> CONTAINER - DELIVERY TO CUSTOMER 
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('shippings.create') }}?load_to_type=2" class="btn btn-sm btn-secondary mt-2">
                              <i class="fas fa-shipping-fast"></i> LORRY - DELIVERY TO SUB CON
                            </a>
                          </li>

                          <li>
                            <a href="{{ route('shippings.scan-qr') }}" class="btn btn-sm btn-warning mt-2">
                              <i class="fas fa-stopwatch"></i> Record Progress - Scan QR
                            </a>
                          </li>
                        </ul>

                      </div>

                    </div>
                    {{--  Shipping Content: END --}}

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
    <div class="modal fade" id="scanQRCodeStockCardProductionModal1" tabindex="-1" aria-labelledby="scanQRCodeStockCardProductionModal1Label" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="scanQRCodeStockCardProductionModal1Label">Scan QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5>Choose Camera</h5>
            <select class="form-select mb-2" id="camera-select1"></select>
            <h5>Cam Preview</h5>
            <canvas id="QRCodeStockCardProductionPlugin1" style="width: 100%"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal 2 -->
    <div class="modal fade" id="scanQRCodeStockCardProductionModal2" tabindex="-1"
      aria-labelledby="scanQRCodeStockCardProductionModal2Label" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="scanQRCodeStockCardProductionModal2Label">Scan QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5>Choose Camera</h5>
            <select class="form-select mb-2" id="camera-select2"></select>
            <h5>Cam Preview</h5>
            <canvas id="QRCodeStockCardProductionPlugin2" style="width: 100%"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal 3 -->
    <div class="modal fade" id="scanQRCodeStockCardProductionModal3" tabindex="-1"
      aria-labelledby="scanQRCodeStockCardProductionModal3Label" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="scanQRCodeStockCardProductionModal3Label">Scan QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5>Choose Camera</h5>
            <select class="form-select mb-2" id="camera-select3"></select>
            <h5>Cam Preview</h5>
            <canvas id="QRCodeStockCardProductionPlugin3" style="width: 100%"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Machine Code -->
    <div class="modal fade" id="scanQRCodeMachineCodeModal" tabindex="-1"
      aria-labelledby="scanQRCodeMachineCodeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="scanQRCodeMachineCodeModalLabel">Scan QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5>Choose Camera</h5>
            <select class="form-select mb-2" id="camera-select4"></select>
            <h5>Cam Preview</h5>
            <canvas id="QRCodeMachineCodePlugin" style="width: 100%"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Worker Code -->
    <div class="modal fade" id="scanQRCodeWorkerCodeModal" tabindex="-1" aria-labelledby="scanQRCodeWorkerCodeModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="scanQRCodeWorkerCodeModalLabel">Scan QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h5>Choose Camera</h5>
            <select class="form-select mb-2" id="camera-select5"></select>
            <h5>Cam Preview</h5>
            <canvas id="QRCodeWorkerCodePlugin" style="width: 100%"></canvas>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('custom_js')
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
  function showDepartments() {
    $('.productionBox').toggle();
    $(".shippingBox").hide();
    $(".qcFormBox").hide();
  }

  $('input[name=department]').change(function () {
    var depId = $(this).val();
    var isMachine = $(this).attr('data-is-machine');

    if(isMachine == 1) {
      $(".machineGroup").show();
    } else {
      $(".machineGroup").hide();
    }

    if(depId > 0) {
      $('.workStatus').removeClass('d-none');
    }
  });

  $('input[name=part_type]').change(function () {
    var partType = $(this).val();

    if(partType == 1) {
      $(".white_part").show();
      $(".finishing_part").hide();
    } else {
      $(".finishing_part").show();
      $(".white_part").hide();
    }
  });

  $('input[name=workStatus]').change(function () {
    var workStatus = $(this).val();
    if(workStatus != 3) {
      $('.workOnCodes').removeClass('d-none');
      $('.searchWorkByCodes').addClass('d-none');
    } else {
      $('.workOnCodes').addClass('d-none');
      $('.searchWorkByCodes').removeClass('d-none');
    }
  });

  $('#worker_ids, #worker_id2').select2({
    width: '100%',
    placeholder: 'Search workers',
  });

  $('#machine_id, #machine_id2').select2({
    width: '100%',
    placeholder: 'Search Machine Code',
  });

  $('#stock_card_ids, #stock_card_id2, #stock_card_id3').select2({
    width: '100%',
    placeholder: 'Search Stock Card Code',
  });

  $(".searchByCode").click(function () {
    var department_id = $("input[name=department]:checked").val();
    var worker_id = $("#worker_id2").val();
    var machine_id = $("#machine_id2").val();
    var stock_card_id = $("#stock_card_id2").val();

    var htmlBody = '';
    $.ajax({
        url: "{{ route('production.daily.process.search.inprogress') }}",
        type: "get",
        data: {
          'department_id': department_id,
          'worker_id': worker_id,
          'machine_id': machine_id,
          'stock_card_id': stock_card_id,
        },
        success: (response) => {
            var productionItems = response;
            if(productionItems.length > 0) {
                $.each(productionItems, function( index, productionItem ) {
                  var machinesList = 'N/A';
                  var WorkersList = 'N/A';
                  var StockCardsList = 'N/A';
                  if(productionItem.machines.length > 0) {
                    machinesList = '';
                    $(productionItem.machines).each(function( index, machineItem ) {
                      machinesList += machineItem.machine.name + '[' + machineItem.machine.code + '], ';
                    });
                  }
                  if(productionItem.workers.length > 0) {
                    WorkersList = '';
                    $(productionItem.workers).each(function( index, workerItem ) {
                      WorkersList += workerItem.worker.name + ', ';
                    });
                  }
                  if(productionItem.stock_cards.length > 0) {
                    StockCardsList = '';
                    $(productionItem.stock_cards).each(function( index, stockCard ) {
                      StockCardsList += stockCard.stock_card.stock_card_number + ', ';
                    });
                  }

                    htmlBody += `
                    <tr>
                        <td>#${ index + 1 }</td>
                        <td>${ productionItem.work_status === 1 ? "NEW ITEM" : "REWORK ITEM" }</td>
                        <td>${ StockCardsList }</td>
                        <td>${ productionItem.total_quantity_plan }</td>
                        <td>${ productionItem.department.name }</td>
                        <td>${ WorkersList }</td>
                        <td>${ machinesList }</td>
                        <td><a href="{{ Url('production/daily') }}/${ productionItem.id }">Continue Work</a></td>
                    </tr>
                    `;
                });
            } else {
                htmlBody = `
                <tr>
                    <td colspan="8" class="text-center">
                        <h3>No Production Work found with that criteria! Try searching again.</h3>
                    </td>
                </tr>
                `;
            }

            $(".productionList tbody").html(htmlBody);
            $(".productionList").removeClass('d-none');
        },
        error: (jqXHR, textStatus, errorThrown) => {
            "Error: " + jqXHR.status;
        }
    })

  });

  // QC Codes
  function showQC() {
    $(".qcFormBox").toggle();
    $('.productionBox').hide();
    $(".shippingBox").hide();
  }
  function showIQC() {
    $(".IQCBox").show();
    $(".IPQCBox").hide();
    $(".FQCBox").hide();
    
  }
  function showIPQC() {
    $(".IPQCBox").show();
    $(".IQCBox").hide();
    $(".FQCBox").hide();
  }
  function showFQC() {
    $(".FQCBox").show();
    $(".IQCBox").hide();
    $(".IPQCBox").hide();
  }

  // Shipping Codes
  function showShipping() {
    $(".shippingBox").toggle();
    $(".qcFormBox").hide();
    $('.productionBox').hide();
  }
</script>

<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/qrcodelib.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/webcodecamjs.js"></script>
<script type="text/javascript">
  // QR Code Scan 1
  var QRCodeStockCardProductionModal1 = document.getElementById('scanQRCodeStockCardProductionModal1');
  var QRCodeStockCardProductionPlugin1 = null;
  QRCodeStockCardProductionModal1.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 1);
        }
    };
    QRCodeStockCardProductionPlugin1 = new WebCodeCamJS("canvas#QRCodeStockCardProductionPlugin1").buildSelectMenu("#camera-select1", "environment|back").init(QRCodeArgs);
    QRCodeStockCardProductionPlugin1.play();
  });
  QRCodeStockCardProductionModal1.addEventListener('hidden.bs.modal', function (event) {
    QRCodeStockCardProductionPlugin1.stop();
    QRCodeStockCardProductionPlugin1 = null;
  });
  document.querySelector("#camera-select1").addEventListener("change", function() {
    if (QRCodeStockCardProductionPlugin1.isInitialized()) {
      QRCodeStockCardProductionPlugin1.stop().play();
    }
  });

  // QR Code Scan 2
  var QRCodeStockCardProductionModal2 = document.getElementById('scanQRCodeStockCardProductionModal2');
  var QRCodeStockCardProductionPlugin2 = null;
  QRCodeStockCardProductionModal2.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 2);
        }
    };
    QRCodeStockCardProductionPlugin2 = new WebCodeCamJS("canvas#QRCodeStockCardProductionPlugin2").buildSelectMenu("#camera-select2", "environment|back").init(QRCodeArgs);
    QRCodeStockCardProductionPlugin2.play();
  });
  document.querySelector("#camera-select2").addEventListener("change", function() {
    if (QRCodeStockCardProductionPlugin2.isInitialized()) {
      QRCodeStockCardProductionPlugin2.stop().play();
    }
  });
  QRCodeStockCardProductionModal2.addEventListener('hidden.bs.modal', function (event) {
    QRCodeStockCardProductionPlugin2.stop();
    QRCodeStockCardProductionPlugin2 = null;
  });

  // QR Code Scan 3
  var QRCodeStockCardProductionModal3 = document.getElementById('scanQRCodeStockCardProductionModal3');
  var QRCodeStockCardProductionPlugin3 = null;
  QRCodeStockCardProductionModal3.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 3);
        }
    };
    QRCodeStockCardProductionPlugin3 = new WebCodeCamJS("canvas#QRCodeStockCardProductionPlugin3").buildSelectMenu("#camera-select3", "environment|back").init(QRCodeArgs);
    QRCodeStockCardProductionPlugin3.play();
  });
  document.querySelector("#camera-select3").addEventListener("change", function() {
    if (QRCodeStockCardProductionPlugin3.isInitialized()) {
      QRCodeStockCardProductionPlugin3.stop().play();
    }
  });
  QRCodeStockCardProductionModal3.addEventListener('hidden.bs.modal', function (event) {
    QRCodeStockCardProductionPlugin3.stop();
    QRCodeStockCardProductionPlugin3 = null;
  });

  // QR Code Scan Machine Code
  var QRCodeMachineCodeModal = document.getElementById('scanQRCodeMachineCodeModal');
  var QRCodeMachineCodePlugin = null;
  QRCodeMachineCodeModal.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 4);
        }
    };
    QRCodeMachineCodePlugin = new WebCodeCamJS("canvas#QRCodeMachineCodePlugin").buildSelectMenu("#camera-select4", "environment|back").init(QRCodeArgs);
    QRCodeMachineCodePlugin.play();
  });
  document.querySelector("#camera-select4").addEventListener("change", function() {
    if (QRCodeMachineCodePlugin.isInitialized()) {
      QRCodeMachineCodePlugin.stop().play();
    }
  });
  QRCodeMachineCodeModal.addEventListener('hidden.bs.modal', function (event) {
    QRCodeMachineCodePlugin.stop();
    QRCodeMachineCodePlugin = null;
  });

  // QR Code Scan Worker Code
  var QRCodeWorkerCodeModal = document.getElementById('scanQRCodeWorkerCodeModal');
  var QRCodeWorkerCodePlugin = null;
  QRCodeWorkerCodeModal.addEventListener('show.bs.modal', function (event) {
    var QRCodeArgs = {
        resultFunction: function(result) {
          searchAndInitQRCode(result.code, 5);
        }
    };
    QRCodeWorkerCodePlugin = new WebCodeCamJS("canvas#QRCodeWorkerCodePlugin").buildSelectMenu("#camera-select5", "environment|back").init(QRCodeArgs);
    QRCodeWorkerCodePlugin.play();
  });
  document.querySelector("#camera-select5").addEventListener("change", function() {
    if (QRCodeWorkerCodePlugin.isInitialized()) {
      QRCodeWorkerCodePlugin.stop().play();
    }
  });
  QRCodeWorkerCodeModal.addEventListener('hidden.bs.modal', function (event) {
    QRCodeWorkerCodePlugin.stop();
    QRCodeWorkerCodePlugin = null;
  });

  function searchAndInitQRCode(QRCode, modalNo) {
    if(modalNo == 1) {
      $('#stock_card_ids').find('option[data-qrcode="'+QRCode+'"]').attr('selected', 'selected');
      
      $('#stock_card_ids').select2({
        width: '100%',
        placeholder: 'Search Stock Card Code',
      });

      $('#scanQRCodeStockCardProductionModal1').modal('hide');
    }

    if(modalNo == 2) {
      $('#stock_card_id2').find('option[data-qrcode="'+QRCode+'"]').attr('selected', 'selected');
      
      $('#stock_card_id2').select2({
        width: '100%',
        placeholder: 'Search Stock Card Code',
      });

      $('#scanQRCodeStockCardProductionModal2').modal('hide');
    }

    if(modalNo == 3) {
      $('#stock_card_id3').find('option[data-qrcode="'+QRCode+'"]').attr('selected', 'selected');
      
      $('#stock_card_id3').select2({
        width: '100%',
        placeholder: 'Search Stock Card Code',
      });

      $('#scanQRCodeStockCardProductionModal3').modal('hide');
    }

    if(modalNo == 4) {
      $('#machine_id').find('option[value="'+QRCode+'"]').attr('selected', 'selected');
      
      $('#machine_id').select2({
        width: '100%',
        placeholder: 'Search Machine Code',
      });

      $('#scanQRCodeMachineCodeModal').modal('hide');
    }

    if(modalNo == 5) {
      $('#worker_ids').find('option[value="'+QRCode+'"]').attr('selected', 'selected');
      
      $('#worker_ids').select2({
        width: '100%',
        placeholder: 'Search Worker Code',
      });

      $('#scanQRCodeWorkerCodeModal').modal('hide');
    }
  }
</script>
@endsection