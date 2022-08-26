@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-cubes"></i> Manage Product</h1>
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
                  <h4 class="card-title">View - Product [{{ $product->model_name }}]
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
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
                                      <label for="parent_product_id">Parent Product </label>
                                      <select disabled class="form-select" id="parent_product_id" name="parent_product_id">
                                        @if(empty($product->parentProduct))
                                        <option></option>
                                        @else
                                        <option value="{{ $product->parentProduct->id }}">{{ $product->parentProduct->model_name . " " .
                                          $product->parentProduct->product_name . " " . $product->parentProduct->color_name . " [" .
                                          $product->parentProduct->color_code . "] " .
                                          $product->parentProduct->length ."x". $product->parentProduct->width ."x". $product->parentProduct->thick }}
                                        </option>
                                        @endif
                                      </select>
                                    </div>
                                    <div class="form-group">
                                      <label for="category_id">Category </label>
                                      <select disabled class="form-select" id="category_id" name="category_id">
                                        <option></option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == old('category_id', $product->category_id)) selected
                                          @endif>{{ $category->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <div class="form-group d-none">
                                      <label for="subcategory_id">Sub Category </label>
                                      <select disabled class="form-select" id="subcategory_id" name="subcategory_id">
                                        <option></option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == old('subcategory_id', $product->subcategory_id))
                                          selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                
                                    <div class="form-group d-none">
                                      <label for="bomcategory_id">BOM Category </label>
                                      <select disabled class="form-select" id="bomcategory_id" name="bomcategory_id">
                                        <option></option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == old('bomcategory_id', $product->bomcategory_id))
                                          selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                
                                    <div class="form-group">
                                      <label for="price_per_unit">Price Per Unit</label>
                                      <input disabled type="number" min="0" class="form-control" id="price_per_unit" name="price_per_unit"
                                        value="{{ old('price_per_unit', $product->price_per_unit) }}">
                                    </div>
                                
                                    <div class="form-group">
                                      <label for="model_name">Item <span class="required">*</span></label>
                                      <input disabled type="text" class="form-control" id="model_name" name="model_name"
                                        value="{{ old('model_name', $product->model_name) }}" required>
                                    </div>
                                
                                    <div class="form-group">
                                      <label for="material">Material</label>
                                      <select disabled class="form-select" id="material" name="material">
                                        <option></option>
                                        @foreach ($materials as $material)
                                        <option value="{{ $material->name }}" @if ($material->name == old('material', $product->material)) selected
                                          @endif>{{ $material->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                  </div>
                                
                                  <div class="col-md-8">
                                    <div class="form-group">
                                      <label for="item_description">Description</label>
                                      <br>
                                      {!! $product->item_description !!}
                                    </div>
                                  </div>
                                
                                  <div class="col-md-12 mt-3">
                                    <h3><i class="fas fa-swatchbook"></i> Model Variants</h3>
                                    <div class="table-responsive productVariantsList">
                                      <table class="table table-striped table-hover table-bordered align-middle">
                                        <thead>
                                          <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Product Number</th>
                                            <th scope="col">Color</th>
                                            <th scope="col">Length & Unit</th>
                                            <th scope="col">Width & Unit</th>
                                            <th scope="col">Thick & Unit</th>
                                            <th scope="col">Images</th>
                                            <th scope="col">PDF</th>
                                          </tr>
                                        </thead>
                                
                                        <tbody>
                                          @foreach($productVariants as $productVariant)
                                          <tr>
                                            <td>
                                              #{{ $loop->iteration }}
                                            </td>
                                            <td>
                                              {{ $productVariant->product_name }}
                                            </td>
                                            <td>
                                              {{ $productVariant->color_name . ' ' . $productVariant->color_code }}
                                            </td>
                                            <td>
                                              {{ $productVariant->length . ' ' . $productVariant->length_unit }}
                                            </td>
                                            <td>
                                              {{ $productVariant->width . ' ' . $productVariant->width_unit }}
                                            </td>
                                            <td>
                                              {{ $productVariant->thick . ' ' . $productVariant->thick_unit }}
                                            </td>
                                            <td>
                                              <ul class="list-inline mt-2">
                                                @foreach($productVariant->productPictures as $picture)
                                                <li class="list-inline-item text-center picture_{{ $picture->id }}">
                                                  <a href="{{ Url('') }}/uploads/{{ $picture->picture_link }}" target="_blank">
                                                    <img src="{{ Url('') }}/uploads/{{ $picture->picture_link }}" alt="picture" class="img-thumbnail"
                                                      style="width: 100px; height: 100px;">
                                                  </a>
                                                </li>
                                                @endforeach
                                              </ul>
                                            </td>
                                            <td>
                                              @if($productVariant->pdf_url)
                                              <a href="{{ Url('') }}/uploads/{{ $productVariant->pdf_url }}" target="_blank">
                                                PDF File
                                              </a>
                                              @endif
                                            </td>
                                          </tr>
                                          @endforeach
                                        </tbody>
                                      </table>
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

@section('custom_js')
<script src="{{ url('/assets') }}/plugins/ckeditor/ckeditor.js"></script>
<script>
  // CKeditor Config
  var ckEditorConfig = {
    toolbar: {
      items: []
    },
    language: 'en',
    licenseKey: '',
  };

  // Ckeditor For Description
  ClassicEditor.create( document.querySelector( '.editor' ), ckEditorConfig )
    .then( editor => {
      window.editor = editor;
    })
    .catch( error => {
      console.error( 'Oops, something went wrong!' );
      console.error( error );
    });

  //Ckeditor For Packing
  ClassicEditor.create( document.querySelector( '.editor2' ), ckEditorConfig )
    .then( editor => {
      window.editor = editor;
    })
    .catch( error => {
      console.error( 'Oops, something went wrong!' );
      console.error( error );
    });
</script>
@endsection