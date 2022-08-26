@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-user-shield"></i> Manage Roles</h1>
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
                  <h4 class="card-title">Edit - Role#{{ $role->id }}
                  <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('roles.update', $role->id) }}">
                              <div class="row">
                                  <div class="col-md-6">
                                      @csrf
                                      {{ method_field('PUT') }}
                                      <div class="form-group">
                                          <label for="name">Name <span class="required">*</span></label>
                                          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                                      </div>
                                  </div>
                              </div>

                              <h4 class="mt-4">Permissions:</h4>

                              @php
                              $currentPermissions = (json_decode($role->permissions, TRUE))['permissions'];
                              @endphp

                              <div class="row">
                                @foreach ($permissions as $key => $value)
                                <div class="col-md-3">
                                  <div class="card mt-1">
                                    <div class="card-body">
                                      <h5>{{ ucwords(str_replace("-"," ",$key)) }}:</h5>
                                      @foreach ($value as $permission)
                                      <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="{{ $key }}-{{ $permission }}"
                                          name="permissions[{{ $key }}][{{ $permission }}]" @if (isset($currentPermissions[$key][$permission]) )
                                          checked @endif>
                                        <label class="form-check-label" for="{{ $key }}-{{ $permission }}">{{ ucwords(str_replace("-"," ",$permission)) }}</label>
                                      </div>
                                      @endforeach
                                  
                                    </div>
                                  </div>
                                </div>
                                @endforeach
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