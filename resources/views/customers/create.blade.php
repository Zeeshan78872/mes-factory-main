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
                  <h4 class="card-title">Add New
                  <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('customers.store') }}">
                                <div class="row">
                                    <div class="col-md-4">
                                        @csrf
                                        <div class="form-group">
                                            <label for="customer_name">Customer Name <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                        </div>

                                        <div class="form-group">
                                          <label for="company_name">Company Name</label>
                                          <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}">
                                        </div>

                                        <div class="form-group">
                                          <label for="country_name">Country Name</label>
                                          <input type="text" class="form-control" id="country_name" name="country_name" value="{{ old('country_name') }}">
                                        </div>

                                        <div class="form-group">
                                          <label for="country_code">Country Code</label>
                                          <input type="text" class="form-control" id="country_code" name="country_code" value="{{ old('country_code') }}">
                                        </div>

                                    </div>

                                    <div class="col-md-4">
                                      
                                      <label for="emails">Email IDs</label>
                                      <div class="emailsBox">
                                        <div class="form-group">
                                          <input type="email" class="form-control" name="emails[]">
                                        </div>
                                      </div>
                                      
                                      <button type="button" class="addEmails btn btn-primary btn-sm float-end mt-2">
                                        <i class="far fa-envelope"></i> Add More Email ID
                                      </button>

                                    </div>

                                    <div class="col-md-4">
                                    
                                      <label for="phoneNos">Phone Numbers</label>
                                      <div class="phoneNosBox">
                                        <div class="form-group">
                                          <input type="text" class="form-control" name="phoneNos[]">
                                        </div>
                                      </div>
                                    
                                      <button type="button" class="addPhoneNos btn btn-primary btn-sm float-end mt-2">
                                        <i class="fas fa-phone-alt"></i> Add More Phone Number
                                      </button>
                                    
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Add</button>
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
<script>
  // Add Email IDs
  $(".addEmails").click(function(e) {
    e.preventDefault();
    $(".emailsBox").append('<div class="form-group mt-2"><input type="text" class="form-control" name="emails[]"><a href="#" class="remove_email_field text-danger">Remove Email ID</a></div>');
  });

  $(".emailsBox").on("click",".remove_email_field", function(e) {
    e.preventDefault();
    $(this).parent('div').remove();
  });

  // Add Phone Numbers
  $(".addPhoneNos").click(function(e) {
    e.preventDefault();
    $(".phoneNosBox").append('<div class="form-group mt-2"><input type="text" class="form-control" name="phoneNos[]"><a href="#" class="remove_phone_field text-danger">Remove Phone No</a></div>');
  });

  $(".phoneNosBox").on("click",".remove_phone_field", function(e) {
    e.preventDefault();
    $(this).parent('div').remove();
  });
</script> 
@endsection