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
                  <h4 class="card-title">Edit - Product Category#{{ $category->id }}
                  <a href="{{ route('product.categories.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('product.categories.update', $category->id) }}">
                                <div class="row">
                                  <div class="col-md-4">
                                    @csrf
                                    {{ method_field('PUT') }}
                                    <div class="form-group">
                                      <label for="category_name">Category Name <span class="required">*</span></label>
                                      <input type="text" class="form-control" id="category_name" name="category_name" value="{{ old('category_name', $category->name) }}"
                                        required>
                                    </div>
                                    <div class="form-group">
                                      <label for="category_code">Category Code <span class="required">*</span></label>
                                      <input type="text" class="form-control" id="category_code" name="category_code" value="{{ old('category_code', $category->code) }}"
                                        required>
                                    </div>

                                    <div class="form-group">
                                        <label for="has_bom_items">Has BOM Items</label>
                                        <input type="checkbox" id="has_bom_items" name="has_bom_items" {{ $category->has_bom_items ? 'checked' : '' }}>
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