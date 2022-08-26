@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-users"></i> Manage Customers</h1>
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
                  <h4 class="card-title">View - Customer#{{ $customer->id }}
                    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
                  </h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form>
                                <div class="row">
                                    <div class="col-md-4">
                                      @csrf
                                      {{ method_field('PUT') }}
                                      <div class="form-group">
                                        <label for="customer_name">Customer Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" value="{{ $customer->customer_name }}" disabled>
                                      </div>
                                    
                                      <div class="form-group">
                                        <label for="company_name">Company Name</label>
                                        <input type="text" class="form-control" value="{{ $customer->company_name }}" disabled>
                                      </div>
                                    
                                      <div class="form-group">
                                        <label for="country_name">Country Name</label>
                                        <input type="text" class="form-control" value="{{ $customer->country_name }}" disabled>
                                      </div>
                                    
                                      <div class="form-group">
                                        <label for="country_code">Country Code</label>
                                        <input type="text" class="form-control" value="{{ $customer->country_code }}" disabled>
                                      </div>
                                    
                                    </div>
                                    
                                    <div class="col-md-4">
                                    
                                      <label for="emails">Email IDs</label>
                                      <div class="emailsBox">
                                        @if (count($customer->emailIds) === 0)
                                        <h4>N/A</h4>
                                        @endif

                                        @foreach($customer->emailIds as $email)
                                        <div class="form-group mt-2">
                                          <input type="text" class="form-control" value="{{ $email->email_id }}" disabled>
                                        </div>
                                        @endforeach
                                      </div>
                                    
                                    </div>
                                    
                                    <div class="col-md-4">
                                    
                                      <label for="phoneNos">Phone Numbers</label>
                                      <div class="phoneNosBox">
                                        @if (count($customer->phoneNos) === 0)
                                        <h4>N/A</h4>
                                        @endif

                                        @foreach($customer->phoneNos as $phone)
                                        <div class="form-group mt-2">
                                          <input type="text" class="form-control" value="{{ $phone->phone_number }}" disabled>
                                        </div>
                                        @endforeach
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