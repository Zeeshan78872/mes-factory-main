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
              <i class="fas fa-pallet"></i> Return Item from Production <a
                href="{{ route('inventory.list.for.production') }}" class="btn btn-sm btn-primary float-end"><i
                  class="fas fa-chevron-left"></i> Go
                Back</a>
            </h4>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <form method="post" action="{{ route('inventory.store.return.from.production', $item->id) }}">
                  <div class="row">
                    <div class="col-md-6">
                      @csrf

                      <div class="form-group">
                        <label for="quantity">Quantity <span class="required">*</span></label>
                        <input type="text" class="form-control" name="quantity" value="1" required>
                      </div>

                      <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks"></textarea>
                      </div>

                      <div class="col-md-12">
                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Save</button>
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

@section('custom_js')
@endsection