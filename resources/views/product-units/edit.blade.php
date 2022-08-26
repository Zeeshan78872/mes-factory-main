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
                  <h4 class="card-title">Edit - Product Unit#{{ $unit->id }}
                  <a href="{{ route('product.units.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('product.units.update', $unit->id) }}">
                                <div class="row">
                                  <div class="col-md-4">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="form-group">
                                      <label for="unit_name">Unit Name <span class="required">*</span></label>
                                      <input type="text" class="form-control" id="unit_name" name="unit_name" value="{{ old('unit_name', $unit->name) }}"
                                        required>
                                    </div>

                                    <div class="form-group">
                                      <label for="unit_code">Unit Codze <span class="required">*</span></label>
                                      <input type="text" class="form-control" id="unit_code" name="unit_code" value="{{ old('unit_code', $unit->code) }}"
                                        required>
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
@endsection