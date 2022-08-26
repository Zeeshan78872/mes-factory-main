@extends('layouts.app')

@section('custom_css')
<link rel="stylesheet" href="{{ url('/assets') }}/plugins/select2/css/select2.min.css">
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-tractor"></i> Daily Production</h1>
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
                        <h4 class="card-title">
                            <i class="fas fa-chalkboard-teacher"></i> Production Inprocess
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li>Work Status: <b>{{ $productionItem->work_status == 1 ? 'New Item' : 'Rework Item' }}</b></li>
                                    <li>
                                        Stock Cards:
                                        @if(!$productionItem->stockCards->isEmpty())
                                            {{$stock_card_name = ""}}
                                            @foreach ($productionItem->stockCards as $stockCard)
                                            <b>{{ $stockCard->stockCard->stock_card_number }}</b>,
                                            <span style="display: none">{{ $stock_card_name =$stockCard->stockCard->stock_card_number  }}</span>
                                                {{$stock_card_name}}
                                            @endforeach
                                        @else
                                        N/A
                                        @endif
                                    </li>
                                    <li>Total Qty Plan: <b>{{ $productionItem->total_quantity_plan }}</b></li><li>Testing Speed: <b>{{ $productionItem->testing_speed }}</b></li>
                                    <li>Department: <b>{{ $productionItem->department->name }} [{{ $productionItem->department->code }}]</b></li>
                                    <li>
                                        Workers:
                                        @if(!$productionItem->workers->isEmpty())
                                            @foreach ($productionItem->workers as $worker)
                                            <b>[{{ $worker->worker->id }}]{{ $worker->worker->name }}</b>,
                                            @endforeach
                                        @else
                                        N/A
                                        @endif
                                    </li>
                                    <li>
                                        Machines:
                                        @if(!$productionItem->machines->isEmpty())
                                            @foreach ($productionItem->machines as $machine)
                                            <b>{{ $machine->machine->name }} [{{ $machine->machine->code }}]</b>,
                                            @endforeach
                                        @else
                                        N/A
                                        @endif
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6 mt-4 mb-5">
                                        <div class="alert alert-primary text-center">
                                            <h4>When Working on Production Click Below:</h4>

                                            <button type="button" class="btn btn-primary productionBtnStart" onclick="productionTimerStart()"><i
                                                    class="fas fa-stopwatch"></i> START PRODUCTION</button>
                                            <button type="button" class="btn btn-danger productionBtnStop d-none" onclick="productionTimerStop()"><i
                                                    class="fas fa-stopwatch"></i> STOP PRODUCTION</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4 mb-5">
                                        <div class="alert alert-warning text-center">
                                            <h4>When Going for a break Click Below:</h4>

                                            <button type="button" class="btn btn-success breakBtnStart" onclick="breakTimerStart()"><i class="fas fa-coffee"></i>
                                                START BREAK</button>
                                            <button type="button" class="btn btn-danger breakBtnStop d-none" onclick="breakTimerStop()"><i
                                                    class="fas fa-coffee"></i> STOP BREAK</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 mt-4 mb-5">
                                <hr>
                                <h4>Progress List:</h4>
                                <hr>
                                <div class="table-responsive productionList">
                                    <table class="table table-striped table-hover table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Start</th>
                                                <th scope="col">End</th>
                                                <th scope="col">Duration</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4 mb-5">
                                <div class="alert alert-danger text-center">
                                    <h4>When Production Completed Click Below:</h4>
                                    <button type="button" class="btn btn-danger endbtn mb-2" onclick="endProduction()"><i class="fas fa-stop-circle"></i>
                                        END PRODUCTION</button>
                                </div>
                            </div>


                            <div class="col-md-12 endProductionForm d-none">
                                <form method="post" action="{{ route('production.daily.update', $productionItem->id) }}">
                                    <div class="row">
                                        @csrf
                                        <div class="col-md-4">
                                            <h4 id="endProductionHeading">Fill the following to End Production:</h4>

                                            <div class="form-group">
                                                <label for="testing_speed">Testing Speed</label>
                                                <input type="text" class="form-control" id="testing_speed" name="testing_speed[]" value="{{ $productionItem->testing_speed }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="total_qty_produce">Total Qty Produced<span class="required">*</span></label>
                                                <input type="number" min="0" value="1" class="form-control" id="total_qty_produce" name="total_qty_produce[]">
                                            </div>
                                            <div class="form-group">
                                                <label for="total_qty_reject">Total Qty Rejected<span class="required">*</span></label>
                                                <input type="number" min="0" value="0" class="form-control" id="total_qty_reject" name="total_qty_reject[]">
                                            </div>

                                            <div class="alert alert-danger qtyNotice mt-2" style="display: none;">
                                                <i class="fas fa-exclamation-triangle"></i> Total QTY produced and Total QTY rejected must match the Total QTY Planned.
                                            </div>

                                            <input type="hidden" name="has_chemical" value="{{ $productionItem->department->is_chemical }}">
                                            <input type="hidden" name="has_assembly" value="{{ $productionItem->department->is_assembly }}">
                                        </div>

                                        @if($productionItem->department->is_assembly || !$productionItem->department->is_chemical)
                                        <div class="col-md-8">
                                            <h3>{{$productionItem->department->is_assembly ? 'Assembled/Packed Product' : 'Select Product'}}</h3>
                                            
                                            <button type="button" class="btn btn-sm btn-primary addMoreChemicals"><i class="fas fa-plus"></i> Add Products</button>
                                            <div class="table-responsive assemblyList">
                                                <table class="table table-striped table-hover table-bordered align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Product Name</th>
                                                            <th scope="col">Stock Cards</th>
                                                            <th scope="col">Qty</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody >
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                        </div>
                                        @endif

                                        @if($productionItem->department->is_chemical)
                                        <div class="col-md-8">
                                            <h3>Chemicals</h3>

                                            <div class="table-responsive chemicalsList">
                                                <table class="table table-striped table-hover table-bordered align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Chemical Stock Card</th>
                                                            <th scope="col">Method</th>
                                                            <th scope="col">Qty (L)</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5">
                                                                <button type="button" class="btn btn-sm btn-primary addMoreChemicals"><i class="fas fa-plus"></i> Add More Chemicals</button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                        </div>
                                        @endif

                                        <div class="col-md-12">
                                            <div class="form-group mt-5">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
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

<!-- Modal 1 -->
<div class="modal fade" id="scanQRCodeChemicalModal" tabindex="-1"
    aria-labelledby="scanQRCodeChemicalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanQRCodeChemicalModalLabel">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Choose Camera</h5>
                <select class="form-select mb-2" id="camera-select"></select>
                <h5>Cam Preview</h5>
                <canvas id="scanQRCodeChemicalPlugin" style="width: 100%"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addMultipleProducts" tabindex="-1" aria-labelledby="addMultipleProducts" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanQRCodeStockCardProductionModal1Label">Select Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="multi-product-table" class="table table-bordered table-striped" style="width:100%;">
        
                 
                 
                <thead>
                        <tr>
                            <th><input onclick="$('#multi-product-table tbody input[type=checkbox]').prop('checked',this.checked)" type="checkbox" id="selectAll"></th>
                            <th>Product No</th>
                            <th>Item</th>
                            <th>Color Name</th>
                            <th>Color Code</th>
                            <th>Stock Card</th>
                        </tr>

                        <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                    </thead>
                    <tbody id="stock-card-products">
                    </tbody>
                </table>
                <button id="AddItemsInTable" class="btn btn-success" data-bs-dismiss="modal">Add Items</button>
            </div>
        </div>
    </div>
</div>
@endsection
<link href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
@section('custom_js')
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment-duration-format.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/qrcodelib.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/webcodecamjs/js/webcodecamjs.js"></script>
<script>
    var table;
    // Get Server Date Time
    async function getDateTime() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "{{ Url('/datetime') }}",
                type: "get",
                success: (response) => {
                    resolve(response.date_time);
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    reject("Error: " + jqXHR.status);
                }
            })
        })
    }

    async function productionTimerStart() {
        if(!confirm("Are you sure?")) {
            return;
        }

        let serverDateTimeVal = await getDateTime();
        timerStart(1, serverDateTimeVal);
    }

    async function breakTimerStart() {
        if(!confirm("Are you sure?")) {
            return;
        }

        let serverDateTimeVal = await getDateTime();
        timerStart(2, serverDateTimeVal);
    }

    function productionTimerStop() {
        if(!confirm("Are you sure?")) {
            return;
        }

        timerStop(1);
    }

    function breakTimerStop() {
        if(!confirm("Are you sure?")) {
            return;
        }

        timerStop(2);
    }

    function timerStart(timerType=1, serverDateTimeVal) {
        $.ajax({
            url: "{{ route('production.daily.progress.start') }}",
            type: "post",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'start_date': serverDateTimeVal,
                'timer_type': timerType,
                'production_id': "{{ $productionItem->id }}",
            },
            success: (response) => {
                if(response.status) {

                    if(timerType == 1) {
                        $('.productionBtnStart').addClass('d-none');
                        $('.productionBtnStop').removeClass('d-none');
                    } else {
                        $('.breakBtnStart').addClass('d-none');
                        $('.breakBtnStop').removeClass('d-none');
                    }

                    localStorage.setItem('progressId', response.id);
                    localStorage.setItem('productionId', {{ $productionItem->id }});
                } else {
                    alert('Something Went Wrong, Start Again!');
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                reject("Error: " + jqXHR.status);
            }
        });
    }

    function timerStop(timerType=1) {
        var progressId = parseInt(localStorage.getItem('progressId'));
        var productionId = parseInt(localStorage.getItem('productionId'));
        var currentProgressId = {{ $productionItem->id }};

        if(productionId != currentProgressId) {
            alert('Invalid Production Process. You need to refresh to this page!');
            return;
        }

        if(progressId < 1) {
            alert('No progress started yet!');
        }

        var routeUrl = "{{ route('production.daily.progress.stop', ':id')}}";
        routeUrl = routeUrl.replace(':id', progressId);

        $.ajax({
            url: routeUrl,
            type: "post",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'production_id': "{{ $productionItem->id }}",
            },
            success: (response) => {
                if(timerType == 1) {
                    $('.productionBtnStart').removeClass('d-none');
                    $('.productionBtnStop').addClass('d-none');
                } else {
                    $('.breakBtnStart').removeClass('d-none');
                    $('.breakBtnStop').addClass('d-none');
                }
                localStorage.removeItem('progressId');

                getProductionProgresses();
            },
            error: (jqXHR, textStatus, errorThrown) => {
                reject("Error: " + jqXHR.status);
            }
        });

    }

    $(".addMoreChemicals").on('click',function(){
        
        var tableData;
                        $.ajax({
            url: "{{ route('productions.stock-card') }}",
            type: "get",
            success: (response) => {
                console.log(response);
                var jsonData = JSON.parse(response);
                for(var i = 0;i < jsonData.length;i++){
                    tableData += `<tr>
                    <td><input value="${jsonData[i].id+ ' - '+jsonData[i].product_id+' - '+jsonData[i].stock_card_number+' ['+jsonData[i].date_in+'] - '+jsonData[i].product_name}" type="checkbox"></td>
                            <td>
                                ${jsonData[i].product_name}
                                </td>
                                <td>
                                ${jsonData[i].model_name}
                                </td>
                                <td>
                                ${jsonData[i].color_name}
                                </td>
                                <td>
                                ${jsonData[i].color_code}
                                </td>
                            <td>
                                ${jsonData[i].stock_card_number}
                            </td>
                        </tr>`
                }
                $("#stock-card-products").html(tableData);
                $("#addMultipleProducts").modal('toggle');
                // if(table){
                //     table.destroy();
                // }
        table = $('#multi-product-table').DataTable({
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.header()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
        },


    });
            },
            error: (jqXHR, textStatus, errorThrown) => {
                "Error: " + jqXHR.status;
            }
        })       
    });

    $("#AddItemsInTable").on('click',function (){
      var selected = [];
      var tableData = "";
      $('#multi-product-table tbody input[type=checkbox]:checked').each(function(a,b){
          selected.push($(b).val())
      })
      table.destroy();
      var assembly = <?=$productionItem->department->is_assembly?>;
      var chemical = <?=$productionItem->department->is_chemical?>;
      if(assembly || !chemical){
          console.log("hosakta hai aysai");
          for(var i = 0; i < selected.length;i++){
          console.log(selected[i]);
          var temp = selected[i].split(" ");
          console.log(temp);
        tableData += `<tr>
                        <td>
                        <div class="form-group">
                            <label for="assembled_product_id">Select Product (it will generate new stock card)<span class="required">*</span></label>
                            <input class="form-control" value="${selected[i]}" required>
                            <input type="hidden" class="form-control" name="assembled_product_id[]" value="${temp[2]}" required>
                        </div>
                        </td>
                        <td>
                        <div class="form-group">
                            <label for="select_stock_card">Select Product (it will generate new stock card)<span class="required">*</span></label>
                            <select class="form-control" id="select_stock_card" name="select_stock_card[]" required>
                            <option value="${temp[0]}">${temp[4]}</option>
                            <option value="create_new">Create New</option>
                            
                        </div>
                        </td>
                        <td><div class="input-group input-group-sm">
                                <input type="number" step=".01" class="form-control" name="chemical_qty[]" value="0" required>
                            </div></td>
                        <td><a href="javascript:void(0)" class="btn btn-sm btn-danger removeProductBtn"><i class="far fa-trash-alt"></i></a></td>
                        </tr>`;
                    }
                    $(".assemblyList tbody").append(tableData);
                    $('.removeProductBtn').on('click', function() {
                        console.log("delete")
                        if(confirm('Are you sure?')) {
                            $(this).parent().parent().remove();
                        }
                    });

      }else{
      for(var i = 0; i < selected.length;i++){
          console.log(selected[i]);
          var temp = selected[i].split(" ");
        tableData += `<tr>
                        <td>
                            <input class="form-control" value="${selected[i]}" required>
                            <input type="hidden" class="form-control" name="chemical_stock_card_ids[]" value="${temp[0]}" required>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" name="chemical_method[]" required>
                                <option value="Disc">Disc</option>
                                <option value="Tangan">Tangan</option>
                            </select>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step=".01" class="form-control" name="chemical_qty[]" value="0" required>
                                <span class="input-group-text">Liter</span>
                            </div>
                        </td>
                        <td><a href="javascript:void(0)" class="btn btn-sm btn-danger removeChemicalBtn"><i class="far fa-trash-alt"></i></a></td>
                    </tr>`;
      }
    }
      $(".chemicalsList tbody").append(tableData);
    });


    function endProduction() {
        if(!confirm("Are you sure?")) {
            return;
        }
        $(".endProductionForm").removeClass('d-none');
        setTimeout(() => {
            $('html, body').animate({
                scrollTop: $("#endProductionHeading").offset().top
            }, 300);
        }, 200);
    }

    function getProductionProgresses(firstTime=0) {
        var htmlBody = '';
        var lastTimerType = 1;
        var progressId = false;
        var productionId = false;
        var isEnded = null;

        $.ajax({
            url: "{{ route('production.daily.process.progresses', $productionItem->id) }}",
            type: "get",
            success: (response) => {
                var progresses = response.progresses;
                if(progresses.length > 0) {
                    $.each(progresses, function( index, progress ) {
                        progressId = progress.id;
                        productionId = progress.daily_production_id;
                        isEnded = progress.ended_at;
                        lastTimerType = parseInt(progress.timer_type);
                        var timeReadable = moment.duration(progress.difference_seconds, "seconds").format("h [hrs], m [min], s [sec]");
                        htmlBody += `
                        <tr>
                            <td>#${ index+1 }</td>
                            <td>${ lastTimerType == 1 ? 'Production' : 'Break' } </td>
                            <td>${ progress.started_at }</td>
                            <td>${ progress.ended_at ?? 'Not Stopped Yet!'}</td>
                            <td>${ timeReadable }</td>
                        </tr>
                        `;
                    });
                } else {
                    htmlBody = `
                    <tr>
                        <td colspan="5" class="text-center">
                            <h3>No Work done yet</h3>
                        </td>
                    </tr>
                    `;
                }

                $(".productionList tbody").html(htmlBody);

                if(firstTime === 1 && isEnded === null) {
                    if(progressId && productionId) {
                        localStorage.setItem('progressId', progressId);
                        localStorage.setItem('productionId', productionId);
                    }
                    // Prepare Last Timer
                    preparePreviousTimer(lastTimerType, progressId, productionId);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                "Error: " + jqXHR.status;
            }
        })
    }

    // If any previous Timer Was Started
    function preparePreviousTimer(lastTimerType, progressId=false, productionId=false) {
        var progressId = parseInt(progressId ?? localStorage.getItem('progressId'));
        var productionId = parseInt(productionId ?? localStorage.getItem('productionId'));
        var currentProgressId = {{ $productionItem->id }};

        if(progressId > 0 && productionId == currentProgressId) {

            if(lastTimerType === 1) {
                $('.productionBtnStart').addClass('d-none');
                $('.productionBtnStop').removeClass('d-none');
            } else {
                $('.breakBtnStart').addClass('d-none');
                $('.breakBtnStop').removeClass('d-none');
            }
        }
    }

    // Get All Progress Lists
    getProductionProgresses(1);

    @if($productionItem->department->is_chemical)


    $(document.body).delegate('.chemicalSelects', 'change', function() {
        var currentVal = $(this).val();
        var currentIndex = $('.chemicalSelects').index($(this));
        var allSelects = $('select.chemicalSelects');

        var counter = 0;
        $.each($("select.chemicalSelects"), function() {
            var selectedVal = $(this).val();
            var selectedIndex = counter;
            if(currentVal == selectedVal && currentIndex != selectedIndex) {
                $(this).val('').trigger('change');
                alert('you have already selected that chemical before');
            }
            counter++;
        });
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
      width: '70%',
      placeholder: 'Scan/Search Chemical Stock Card',
      minimumInputLength: 1,
      ajax: {
        url: '{{ route('stock-cards.ajax.search') }}',
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
    $('.stockCardOptions').select2(ajaxStockCardsOptions);

    $(document).on('click', '.removeChemicalBtn', function() {
        console.log("delete")
        if(confirm('Are you sure?')) {
            $(this).parent().parent().remove();
        }
    });
    



    // QR Code Scan
    var scanQRCodeChemicalModal = document.getElementById('scanQRCodeChemicalModal');
    var scanQRCodeChemicalPlugin = null;
    scanQRCodeChemicalModal.addEventListener('show.bs.modal', function (event) {
        var QRCodeArgs = {
            resultFunction: function(result) {
                searchAndInitQRCode(result.code);
            }
        };
        scanQRCodeChemicalPlugin = new WebCodeCamJS("canvas#scanQRCodeChemicalPlugin").buildSelectMenu("#camera-select", "environment|back").init(QRCodeArgs);
        scanQRCodeChemicalPlugin.play();
    });
    document.querySelector("#camera-select").addEventListener("change", function() {
        if (scanQRCodeChemicalPlugin.isInitialized()) {
        scanQRCodeChemicalPlugin.stop().play();
        }
    });
    scanQRCodeChemicalModal.addEventListener('hidden.bs.modal', function (event) {
        scanQRCodeChemicalPlugin.stop();
        scanQRCodeChemicalPlugin = null;
    });

    function searchAndInitQRCode(QRCode) {
        $('.stockCardOptions').find('option[data-qrcode="'+QRCode+'"]').attr('selected', 'selected');
        $('.stockCardOptions').select2(ajaxStockCardsOptions);
        $('#scanQRCodeChemicalModal').modal('hide');
    }

    @endif

    @if($productionItem->department->is_assembly)
    // Ajax Select2 Products
    var ajaxProductsOptions = {
        "language": {
            "noResults": function() {
            return "No Results Found... <a href='{{ route('products.create') }}' target='_blank' class='btn btn-primary btn-sm'><i class='fas fa-cubes'></i> Add New Item</a>";
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        width: '100%',
        placeholder: 'Search Items',
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
    $('.ajax-products').select2(ajaxProductsOptions);
    @endif

    $('#total_qty_produce, #total_qty_reject').change(function() {
        var totalQtyProduce = parseInt($('#total_qty_produce').val());
        var totalQtyReject = parseInt($('#total_qty_reject').val());
        var totalQtytyped = parseInt(totalQtyProduce + totalQtyReject);
        var totalQtyPlan = parseInt({{ $productionItem->total_quantity_plan }});

        if(totalQtytyped != totalQtyPlan) {
            $('.qtyNotice').show();
        } else {
            $('.qtyNotice').hide();
        }
    });
</script>
@endsection
