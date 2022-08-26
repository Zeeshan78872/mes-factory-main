@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-superscript"></i> Manage Product Units</h1>
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
                  <h4 class="card-title">View - Product Unit#{{ $unit->id }}
                    <a href="{{ route('product.units.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form>
                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="unit_name">Unit Name</label>
                                      <input type="text" class="form-control" id="unit_name" name="unit_name"
                                        readonly value="{{ $unit->name }}">
                                    </div>

                                    <div class="form-group">
                                      <label for="unit_code">Unit Code</label>
                                      <input type="text" class="form-control" id="unit_code" name="unit_code"
                                        readonly value="{{ $unit->code }}">
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