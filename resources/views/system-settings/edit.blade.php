@extends('layouts.app')

@section('custom_css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/timepicker/jquery.timepicker.min.css">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-cog"></i> Manage System Settings</h1>
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
                  <h4 class="card-title">Edit</div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('system.settings.update') }}">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        @csrf
                                        {{ method_field('PUT') }}

                                        <div class="form-group">
                                            <label for="basic_time_start">Basic Time Start</label>
                                            <input type="text" class="form-control timepicker" id="basic_time_start" name="basic_time_start" value="{{ old('basic_time_start', $systemSettings->basic_time_start) }}">
                                        </div>
                                        <div class="form-group">
                                          <label for="basic_time_end">Basic Time End</label>
                                          <input type="text" class="form-control timepicker" id="basic_time_end" name="basic_time_end"
                                            value="{{ old('basic_time_end', $systemSettings->basic_time_end) }}">
                                        </div>

                                        <div class="form-group">
                                          <label for="over_time_start">Over Time Start</label>
                                          <input type="text" class="form-control timepicker" id="over_time_start" name="over_time_start"
                                            value="{{ old('over_time_start', $systemSettings->over_time_start) }}">
                                        </div>
                                        <div class="form-group">
                                          <label for="over_time_end">Over Time End</label>
                                          <input type="text" class="form-control timepicker" id="over_time_end" name="over_time_end"
                                            value="{{ old('over_time_end', $systemSettings->over_time_end) }}">
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

@section('custom_js')
<script src="{{ url('/assets') }}/plugins/jquery.mask.min.js"></script>
<script src="{{ url('/assets') }}/plugins/timepicker/jquery.timepicker.min.js"></script>
<script>
  $('.timepicker').timepicker({ 'timeFormat': 'H:i' });
  $('.timepicker').mask('00:00');
</script>
@endsection