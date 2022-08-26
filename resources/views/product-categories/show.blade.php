@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-tags"></i> Manage Product Categories</h1>
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
                  <h4 class="card-title">View - Product Category#{{ $category->id }}
                    <a href="{{ route('product.categories.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
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
                                      <label for="category_name">Category Name</label>
                                      <input type="text" class="form-control" id="category_name" name="category_name"
                                        readonly value="{{ $category->name }}">
                                    </div>
                                    <div class="form-group">
                                      <label for="category_code">Category Code</label>
                                      <input type="text" class="form-control" id="category_code" name="category_code"
                                        readonly value="{{ $category->code }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="has_bom_items">Has BOM Items <span class="required">*</span></label>
                                        <input type="checkbox" id="has_bom_items" name="has_bom_items" value="{{ old('has_bom_items') }}" {{ $category->has_bom_items ? 'checked' : '' }} disabled>
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