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
                  <h4 class="card-title">Add New
                  <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary float-end"><i class="fas fa-chevron-left"></i> Go
                    Back</a></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{ route('products.store') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4">
                                        @csrf
                                        <div class="form-group">
                                            <label for="parent_product_id">Parent Product </label>
                                            <select class="form-select" id="parent_product_id" name="parent_product_id">
                                             <option></option>
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
                                            <input type="number" min="0" step="0.01" class="form-control" id="price_per_unit" name="price_per_unit" value="{{ old('price_per_unit', $product->price_per_unit) }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="model_name">Item <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="model_name" name="model_name" value="{{ old('model_name', $product->model_name) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="product_name">Product Number <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
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
                                        <textarea class="form-control editor" id="item_description" name="item_description">{{ old('item_description', $product->item_description) }}</textarea>
                                          <div class="box">
                                              <div class="js--image-preview"></div>
                                              <div class="upload-options">
                                                  <label>
                                                      <input type="file" class="image-upload" name="image" accept="image/*" />
                                                  </label>
                                              </div>
                                          </div>
                                      </div>

                                    </div>

                                    <div class="col-md-12 mt-3">
                                      <h3><i class="fas fa-swatchbook"></i> Model Variants</h3>
                                      <div class="table-responsive productVariantsList">
                                        <table class="table table-striped table-hover table-bordered align-middle">
                                          <thead>
                                            <tr>
                                              <th scope="col">#</th>
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
                                            <tr>
                                              <td>#1</td>
                                              <td>
                                                <select class="form-select" id="color" name="color[]">
                                                  <option></option>
                                                  @foreach ($colors as $color)
                                                  <option value="{{ $color->name }}_{{ $color->code }}" @if (($color->name. '_' .$color->code) == old('color', $product->color_name . '_' . $product->color_code)) selected
                                                    @endif>{{ $color->name }} {{ $color->code }}</option>
                                                  @endforeach
                                                </select>
                                              </td>
                                              <td>
                                                <div class="input-group input-group-sm">
                                                  <input type="text" class="form-control form-control-sm" name="length[]" value="{{ $preFillType == 'size' ? '' : $product->length }}">
                                                  <span class="input-group-text" >
                                                  <select class="form-select form-select-sm" id="length_unit" name="length_unit[]">
                                                    <option></option>
                                                      @foreach ($units as $unit)
                                                      <option value="{{ $unit->name }}" @if ($unit->name == old('length_unit', $product->length_unit)) selected @endif>{{ $unit->name }}</option>
                                                      @endforeach
                                                  </select>
                                                  </span>
                                                </div>
                                              </td>
                                              <td>
                                                <div class="input-group input-group-sm">
                                                  <input type="text" class="form-control form-control-sm" name="width[]" value="{{ $preFillType == 'size' ? '' : $product->width }}">
                                                  <span class="input-group-text" >
                                                  <select class="form-select form-select-sm" id="width_unit" name="width_unit[]">
                                                    <option></option>
                                                      @foreach ($units as $unit)
                                                      <option value="{{ $unit->name }}" @if ($unit->name == old('width_unit', $product->width_unit)) selected @endif>{{ $unit->name }}</option>
                                                      @endforeach
                                                  </select>
                                                  </span>
                                                </div>
                                              </td>
                                              <td>
                                                <div class="input-group input-group-sm">
                                                  <input type="text" class="form-control form-control-sm" name="thick[]" value="{{ $preFillType == 'size' ? '' : $product->thick }}" >
                                                  <span class="input-group-text" >
                                                  <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
                                                    <option></option>
                                                      @foreach ($units as $unit)
                                                      <option value="{{ $unit->name }}" @if ($unit->name == old('thick_unit', $product->thick_unit)) selected @endif>{{ $unit->name }}</option>
                                                      @endforeach
                                                  </select>
                                                  </span>
                                                </div>
                                              </td>
                                              <td>
                                                <input type="file" class="form-control productPicture" name="pictures[0][]" accept="image/png, image/gif, image/jpeg">
                                              </td>
                                              <td>
                                                <input type="file" class="form-control productPdf" name="pdf[0]" accept="application/pdf">
                                              </td>
                                              <td></td>
                                            </tr>
                                          </tbody>

                                          <tfoot>
                                            <tr>
                                              <td colspan="8">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary addMoreProductVariant" data-variant-type="new"><i class="fas fa-swatchbook"></i> Add New Variant</a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary addMoreProductVariant" data-variant-type="color"><i class="fas fa-fill-drip"></i> Add Color</a>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-primary addMoreProductVariant" data-variant-type="size"><i class="fas fa-ruler-combined"></i> Add Size</a>
                                              </td>
                                            </tr>
                                          </tfoot>

                                        </table>
                                      </div>

                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Add Product</button>
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
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ url('/assets') }}/plugins/ckeditor/ckeditor.js"></script>
<script>
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
      console.error( error );
    });

  var unitsHtml = '';
  @foreach ($units as $unit)
  unitsHtml += `<option value="{{ $unit->name }}">{{ $unit->name }}</option>`;
  @endforeach

  $(".addMoreProductVariant").click(function () {
    var variantType = $(this).attr('data-variant-type');
    var colorName = colorCode = length = lengthUnit = width = widthUnit = thick = thickUnit = colorNameCode = '';

    var lastRowData = $('.productVariantsList tbody tr:last');
    if(variantType == 'color') {
      length = lastRowData.find('input[name="length[]"]').val();
      lengthUnit = lastRowData.find('select[name="length_unit[]"]').val();
      width = lastRowData.find('input[name="width[]"]').val();
      widthUnit = lastRowData.find('select[name="width_unit[]"]').val();
      thick = lastRowData.find('input[name="thick[]"]').val();
      thickUnit = lastRowData.find('select[name="thick_unit[]"]').val();
    }
    if(variantType == 'size') {
      colorNameCode = lastRowData.find('select[name="color[]"]').val();
    }
    var rowCount = $('.productVariantsList tbody tr').length;

    var trBody = `<tr>
                  <td>#${rowCount + 1}</td>
                  <td>
                    <select class="form-select" id="color" name="color[]">
                      <option></option>
                      @foreach ($colors as $color)
                      <option value="{{ $color->name }}_{{ $color->code }}" ${colorNameCode == '{{ $color->name }}_{{ $color->code }}' ? 'selected' : ''}>{{ $color->name }}_{{ $color->code }}</option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control form-control-sm" name="length[]" value="${length}">
                      <span class="input-group-text" >
                      <select class="form-select form-select-sm" id="length_unit" name="length_unit[]">
                        <option></option>
                        ${unitsHtml}
                      </select>
                      </span>
                    </div>
                  </td>
                  <td>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control form-control-sm" name="width[]" value="${width}">
                      <span class="input-group-text" >
                      <select class="form-select form-select-sm" id="width_unit" name="width_unit[]">
                        <option></option>
                        ${unitsHtml}
                      </select>
                      </span>
                    </div>
                  </td>
                  <td>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control form-control-sm" name="thick[]" value="${thick}" >
                      <span class="input-group-text" >
                      <select class="form-select form-select-sm" id="thick_unit" name="thick_unit[]">
                        <option></option>
                        ${unitsHtml}
                      </select>
                      </span>
                    </div>
                  </td>
                  <td>
                    <input type="file" class="form-control productPicture" name="pictures[${rowCount}][]" accept="image/png, image/gif, image/jpeg">
                  </td>
                  <td>
                    <input type="file" class="form-control productPdf" name="pdf[${rowCount}]" accept="application/pdf">
                  </td>
                  <td>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductVariantBtn"><i class="far fa-trash-alt"></i></a>
                  </td>
                </tr>`;
    $(".productVariantsList tbody").append(trBody);

    if(variantType == 'color') {
      var newLastRowData = $('.productVariantsList tbody tr:last');
      newLastRowData.find('select[name="length_unit[]"]').val(lengthUnit);
      newLastRowData.find('select[name="width_unit[]"]').val(widthUnit);
      newLastRowData.find('select[name="thick_unit[]"]').val(thickUnit);
    }

    resetPicturesIndex();
    resetPdfIndex();
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

  $(document).on('click', '.removeProductVariantBtn', function() {
    if(confirm('Are you sure?')) {
      $(this).parent().parent().remove();
      resetPicturesIndex();
      resetPdfIndex();
    }
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
</script>
@endsection
