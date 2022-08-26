@extends('layouts.app')

@section('custom_css')
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">
<style>
  .ck-editor__editable {
    min-height: 100px !important;
  }
</style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><i class="fas fa-cubes"></i> Manage Products</h1>
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
                  <h4 class="card-title">Edit - Product [{{ $product->model_name }}]
                  <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <form method="post" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-4">
                              @csrf
                              {{ method_field('PUT') }}
                              <div class="form-group">
                                <label for="parent_product_id">Parent Product </label>
                                <select class="form-select" id="parent_product_id" name="parent_product_id">
                                  @if(empty($product->parentProduct))
                                  <option></option>
                                  @else
                                  <option value="{{ $product->parentProduct->id }}">{{ $product->parentProduct->model_name . " " .
                                    $product->parentProduct->product_name . " " . $product->parentProduct->color_name . " [" .
                                    $product->parentProduct->color_code . "] " .
                                    $product->parentProduct->length ."x". $product->parentProduct->width ."x". $product->parentProduct->thick }}</option>
                                  @endif
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="category_id">Category </label>
                                <select class="form-select" id="category_id" name="category_id">
                                  <option></option>
                                  @foreach ($categories as $category)
                                  <option value="{{ $category->id }}" @if ($category->id == old('category_id', $product->category_id)) selected @endif>{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                              <div class="form-group d-none">
                                <label for="subcategory_id">Sub Category </label>
                                <select class="form-select" id="subcategory_id" name="subcategory_id">
                                  <option></option>
                                  @foreach ($categories as $category)
                                  <option value="{{ $category->id }}" @if ($category->id == old('subcategory_id', $product->subcategory_id)) selected @endif>{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>

                              <div class="form-group d-none">
                                <label for="bomcategory_id">BOM Category </label>
                                <select class="form-select" id="bomcategory_id" name="bomcategory_id">
                                  <option></option>
                                  @foreach ($categories as $category)
                                  <option value="{{ $category->id }}" @if ($category->id == old('bomcategory_id', $product->bomcategory_id)) selected @endif>{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>

                              <div class="form-group">
                                <label for="price_per_unit">Price Per Unit</label>
                                <input type="number" min="0" class="form-control" id="price_per_unit" name="price_per_unit"
                                  value="{{ old('price_per_unit', $product->price_per_unit) }}">
                              </div>

                              <div class="form-group">
                                <label for="model_name">Item <span class="required">*</span></label>
                                <input type="text" class="form-control" id="model_name" name="model_name"
                                  value="{{ old('model_name', $product->model_name) }}" required>
                              </div>

                              <div class="form-group">
                                <label for="material">Material</label>
                                <select class="form-select" id="material" name="material">
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
                                <textarea class="form-control editor" id="item_description"
                                  name="item_description">{{ old('item_description', $product->item_description) }}</textarea>
                              </div>
                            </div>
                              <div class="box">
                                  <div class="js--image-preview" style="background-image: url('{{ Url('').'/uploads/'.$product->image }}')"></div>
                                  <div class="upload-options">
                                      <label>
                                          <input type="file" class="image-upload" name="image" accept="image/*" />
                                      </label>
                                  </div>
                              </div>


                              <div class="col-md-12 mt-3">
                              <h3><i class="fas fa-swatchbook"></i> Model Variants</h3>
                              <div class="table-responsive productVariantsList">
                                <table class="table table-striped table-hover table-bordered align-middle">
                                  <thead>
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Product No</th>
                                      <th scope="col">Color</th>
                                      <th scope="col">Length & Unit</th>
                                      <th scope="col">Width & Unit</th>
                                      <th scope="col">Thick & Unit</th>
                                      <th scope="col">Images</th>
                                      <th scope="col">PDF</th>
                                      <th scope="col">Action</th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    @foreach($productVariants as $productVariant)
                                    <tr>
                                      <td>
                                        <input type="hidden" name="product_ids[]" value="{{ $productVariant->id }}">
                                        #{{ $loop->iteration }}
                                      </td>
                                      <td>
                                        <input type="text" class="form-control form-control-sm" name="product_name[]" value="{{ $productVariant->product_name }}">
                                      </td>
                                      <td>
                                        <select class="form-select" id="color" name="color[]">
                                          <option></option>
                                          @foreach ($colors as $color)
                                          <option value="{{ $color->name }}_{{ $color->code }}" @if (($color->name. '_' .$color->code) ==
                                            ($productVariant->color_name . '_' . $productVariant->color_code)) selected
                                            @endif>{{ $color->name }} {{ $color->code }}</option>
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <div class="input-group input-group-sm">
                                          <input type="text" class="form-control form-control-sm" name="length[]"
                                            value="{{ $productVariant->length }}">
                                          <span class="input-group-text">
                                            <select class="form-select form-select-sm" id="length_unit" name="length_unit[]">
                                              <option></option>
                                              @foreach ($units as $unit)
                                              <option value="{{ $unit->name }}" @if ($unit->name == old('length_unit', $productVariant->length_unit))
                                                selected @endif>{{ $unit->name }}</option>
                                              @endforeach
                                            </select>
                                          </span>
                                        </div>
                                      </td>
                                      <td>
                                        <div class="input-group input-group-sm">
                                          <input type="text" class="form-control form-control-sm" name="width[]"
                                            value="{{ $productVariant->width }}">
                                          <span class="input-group-text">
                                            <select class="form-select form-select-sm" id="width_unit" name="width_unit[]">
                                              <option></option>
                                              @foreach ($units as $unit)
                                              <option value="{{ $unit->name }}" @if ($unit->name == old('width_unit', $productVariant->width_unit))
                                                selected @endif>{{ $unit->name }}</option>
                                              @endforeach
                                            </select>
                                          </span>
                                        </div>
                                      </td>
                                      <td>
                                        <div class="input-group input-group-sm">
                                          <input type="text" class="form-control form-control-sm" name="thick[]"
                                            value="{{ $productVariant->thick }}">
                                          <span class="input-group-text">
                                            <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
                                              <option></option>
                                              @foreach ($units as $unit)
                                              <option value="{{ $unit->name }}" @if ($unit->name == old('thick_unit', $productVariant->thick_unit))
                                                selected @endif>{{ $unit->name }}</option>
                                              @endforeach
                                            </select>
                                          </span>
                                        </div>
                                      </td>
                                      <td>
                                        <input type="file" class="form-control productPicture" name="pictures[{{ $loop->index }}][]" accept="image/png, image/gif, image/jpeg" multiple>

                                        <ul class="list-inline mt-2">
                                          @foreach($productVariant->productPictures as $picture)
                                          <li class="list-inline-item text-center picture_{{ $picture->id }}">
                                            <a href="{{ Url('') }}/uploads/{{ $picture->picture_link }}" target="_blank">
                                              <img src="{{ Url('') }}/uploads/{{ $picture->picture_link }}" alt="picture" class="img-thumbnail"
                                                style="width: 100px; height: 100px;">
                                            </a>
                                            <br>
                                            <a href="javascript:void(0)" class="text-danger" onclick="removePicture('{{ $picture->id }}')">Remove</a>
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
                                        <input type="file" class="form-control productPdf" name="pdf[{{ $loop->index }}]" accept="application/pdf">
                                      </td>
                                      <td>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductVariantBtn" data-id="{{ $productVariant->id }}"><i class="far fa-trash-alt"></i></a>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>

                            </div>

                            <div class="col-md-12">
                              <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Update Product</button>
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
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
  function removePicture(id) {
    if(confirm('Are you sure? it will permanently delete from product!')) {
      var routeUrl = "{{ route('products.delete.picture', ':id')}}";
      routeUrl = routeUrl.replace(':id', id);

      $.get(routeUrl, function(data, status) {
        if(status == 'success') {
          $(".picture_" + id).remove();
        } else {
          alert('Failed to delete');
        }
      });
    }
  }

  // CKeditor Config
  var ckEditorConfig = {
    toolbar: {
      items: [
        'heading',
        '|',
        'specialCharacters',
        'fontBackgroundColor',
        'fontColor',
        'fontSize',
        'highlight',
        'underline',
        'subscript',
        'superscript',
        'horizontalLine',
        'strikethrough',
        'bold',
        'italic',
        'insertTable',
        'link',
        'alignment',
        'bulletedList',
        'numberedList',
        '|',
        'outdent',
        'indent',
        '|',
        'undo',
        'redo',
        'mediaEmbed',
        'findAndReplace',
        'blockQuote',
        'htmlEmbed'
      ]
    },
    language: 'en',
    table: {
      contentToolbar: [
        'tableColumn',
        'tableRow',
        'mergeTableCells',
        'tableCellProperties',
        'tableProperties'
      ]
    },
    licenseKey: '',
  };

  // Ckeditor For Description
  ClassicEditor.create( document.querySelector( '.editor' ), ckEditorConfig )
    .then( editor => {
      window.editor = editor;
    })
    .catch( error => {
      console.error( 'Oops, something went wrong!' );
    });

    var ajaxStockCardsOptions = {
      "language": {
        "noResults": function() {
          return "No Results Found...";
        }
      },
      escapeMarkup: function (markup) {
        return markup;
      },
      width: '100%',
      placeholder: 'Search Product Model',
      minimumInputLength: 1,
      ajax: {
        url: '{{ route('products.search') }}',
        dataType: 'json',
        delay: 800,
        processResults: function (response) {
          return {
            results: response
          };
        },
        cache: true
      }
    };
  $('#parent_product_id').select2(ajaxStockCardsOptions);

  $(document).on('click', '.removeProductVariantBtn', function() {
    var id = $(this).attr("data-id");
    var token = $("meta[name='csrf-token']").attr("content");

    if(confirm('Are you sure?')) {
      if(id > 0) {
        $.ajax({
            url: base_url + "/products/"+id,
            type: 'DELETE',
            data: {
                "id": id,
                "_token": token,
            },
            success: function () {
              location.reload();
            },
            error: function () {
              location.reload();
            }
        });
      }

      $(this).parent().parent().remove();
      resetPicturesIndex();
      resetPdfIndex();
    }
  });

  function resetPicturesIndex() {
    $('.productVariantsList tbody tr td .productPicture').each(function(index) {
      $( this ).attr("name", "pictures["+index+"][]");
    });
  }

  function resetPdfIndex() {
    $('.productVariantsList tbody tr td .productPdf').each(function(index) {
      $( this ).attr("name", "pdf["+index+"]");
    });
  }
</script>
@endsection
