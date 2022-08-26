@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-bell"></i> Notifications</h1>
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
                  <h4 class="card-title">View - Notification#{{ $notification->id }}
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form>
                                <div class="row">
                                  <div class="col-md-12">
                                    <div class="form-group">
                                      <label for="created_at"><b>Created At:</b> </label>
                                      {{ $notification->created_at }}
                                    </div>
                                    <div class="form-group">
                                      <label for="title"><b>Title:</b> </label>
                                      {{ $notification->title }}
                                    </div>
                                    <div class="form-group">
                                      <label for="description"><b>Description:</b> </label>
                                      {!! $notification->description !!}
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