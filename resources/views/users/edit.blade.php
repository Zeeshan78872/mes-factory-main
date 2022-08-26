@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-users-cog"></i> Manage Users</h1>
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
                  <h4 class="card-title">Edit - User#{{ $user->id }}
                  <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('users.update', $user->id) }}">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="form-group">
                                          <label for="role_id">Role <span class="required">*</span></label>
                                          <select class="form-select" id="role_id" name="role_id" required>
                                            @foreach ($roles as $role)
                                              <option value="{{ $role->id }}" @if ($role->id == $user->role_id) selected @endif>{{ $role->name }}</option>
                                            @endforeach
                                          </select>
                                        </div>

                                        <div class="form-group">
                                          <label for="site_id">Site/Location <span class="required">*</span></label>
                                          <select class="form-select" id="site_id" name="site_id" required>
                                            @foreach ($multiSites as $site)
                                            <option value="{{ $site->id }}" @if ($site->id == $user->site_id) selected @endif>{{ $site->name }} ({{ $site->code }})</option>
                                            @endforeach
                                          </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Name <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="form-group">
                                          <label for="basic_salary">Basic Salary (MYR)</label>
                                          <input type="text" class="form-control" id="basic_salary" name="basic_salary" value="{{ old('basic_salary', $user->basic_salary) }}">
                                        </div>
                                        <div class="form-group">
                                          <label for="overtime_salary">Overtime Salary (MYR)</label>
                                          <input type="text" class="form-control" id="overtime_salary" name="overtime_salary"
                                            value="{{ old('overtime_salary', $user->overtime_salary) }}">
                                        <div class="form-group">
                                            <label for="password">Password (optional)</label>
                                            <input id="password" type="password" class="form-control" name="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
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