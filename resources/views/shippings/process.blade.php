@extends('layouts.app')

@section('custom_css')
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-dolly"></i> Manage Shipping</h1>
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
                            <i class="fas fa-dolly"></i> Shipment Loading Inprocess
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li><b>LOAD TO:</b> {{ $shippingItem->shipping->load_to == 1 ? 'Contena' : 'Lorry' }}</li>
                                    <li><b>TOTAL PLANNED QTY: </b>{{ $shippingItem->total_plan_qty }}</li>
                                    <li><b>WORKER:</b> {{ $shippingItem->worker->name }}</li>
                                    <li><b>JOB ORDER NO:</b> {{ $shippingItem->shipping->jobOrder->order_no_manual }}</li>
                                    <li><b>TRUCK OUT DATE:</b> {{ $shippingItem->shipping->truck_out_date?? "-" }}</li>

                                    <li><b>QC DATE:</b> {{ $shippingItem->shipping->qc_date?? "-" }}</li>
                                    <li><b>BOOKING NO:</b> {{ $shippingItem->shipping->booking_no?? "-" }}</li>
                                    <li><b>CONTAINER NO:</b> {{ $shippingItem->shipping->container_no?? "-" }}</li>
                                    <li><b>SEAL NO:</b> {{ $shippingItem->shipping->seal_no?? "-" }}</li>
                                </ul>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6 mt-4 mb-5">
                                        <div class="alert alert-primary text-center">
                                            <h4>When Working on Loading Click Below:</h4>

                                            <button type="button" class="btn btn-primary productionBtnStart" onclick="productionTimerStart()"><i
                                                    class="fas fa-stopwatch"></i> START LOADING</button>
                                            <button type="button" class="btn btn-danger productionBtnStop d-none" onclick="productionTimerStop()"><i
                                                    class="fas fa-stopwatch"></i> STOP LOADING</button>
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
                                        END SHIPMENT LOADING PROCESS</button>
                                </div>
                            </div>


                            <div class="col-md-12 endProductionForm d-none">
                                <form method="post" action="{{ route('shippings.progress.end', $shippingItem->id) }}">
                                    <div class="row">
                                        @csrf
                                        <div class="col-md-4">
                                            <h4 id="endProductionHeading">Fill the following to End Production:</h4>
                                            <div class="form-group">
                                                <label for="actual_loaded_qty">Actual Loaded QTY<span class="required">*</span></label>
                                                <input type="number" min="0" value="1" class="form-control" id="actual_loaded_qty" name="actual_loaded_qty">
                                            </div>
                                        </div>

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
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment.min.js"></script>
<script type="text/javascript" src="{{ url('/assets') }}/plugins/moment-duration-format.min.js"></script>
<script>
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
            url: "{{ route('shippings.progress.start') }}",
            type: "post",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'start_date': serverDateTimeVal,
                'timer_type': timerType,
                'shipping_item_id': "{{ $shippingItem->id }}",
            },
            success: (response) => {
                if(response.status) {
                    if(timerType === 1) {
                        $('.productionBtnStart').addClass('d-none');
                        $('.productionBtnStop').removeClass('d-none');
                    } else {
                        $('.breakBtnStart').addClass('d-none');
                        $('.breakBtnStop').removeClass('d-none');
                    }

                    localStorage.setItem('shippingProgressId', response.id);
                    localStorage.setItem('shipmentItemId', {{ $shippingItem->id }});
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
        var progressId = localStorage.getItem('shippingProgressId');
        var shipmentId = localStorage.getItem('shipmentItemId');
        var currentShipmentItemId = {{ $shippingItem->id }};

        if(shipmentId != currentShipmentItemId) {
            alert('Invalid Production Process.');
            return;
        }

        if(progressId < 1) {
            alert('No progress started yet!');
        }

        var routeUrl = "{{ route('shippings.progress.stop', ':id')}}";
        routeUrl = routeUrl.replace(':id', progressId);

        $.ajax({
            url: routeUrl,
            type: "post",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                'shipping_item_id': "{{ $shippingItem->id }}",
            },
            success: (response) => {
                if(timerType === 1) {
                    $('.productionBtnStart').removeClass('d-none');
                    $('.productionBtnStop').addClass('d-none');
                } else {
                    $('.breakBtnStart').removeClass('d-none');
                    $('.breakBtnStop').addClass('d-none');
                }
                localStorage.removeItem('shippingProgressId');

                getProductionProgresses();
            },
            error: (jqXHR, textStatus, errorThrown) => {
                reject("Error: " + jqXHR.status);
            }
        });

    }

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

        $.ajax({
            url: "{{ route('shippings.process.progresses', $shippingItem->id) }}",
            type: "get",
            success: (response) => {
                var progresses = response.progresses;
                if(progresses.length > 0) {
                    $.each(progresses, function( index, progress ) {
                        lastTimerType = progress.timer_type;
                        var timeReadable = moment.duration(progress.difference_seconds, "seconds").format("h [hrs], m [min], s [sec]");
                        htmlBody += `
                        <tr>
                            <td>#${ index+1 }</td>
                            <td>${ progress.timer_type === 1 ? 'Loading' : 'Break' } </td>
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

                if(firstTime === 1) {
                    // Prepare Last Timer
                    preparePreviousTimer(lastTimerType);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                "Error: " + jqXHR.status;
            }
        })
    }

    // If any previous Timer Was Started
    function preparePreviousTimer(lastTimerType) {
        var progressId = localStorage.getItem('shippingProgressId');
        var shipmentItemId = localStorage.getItem('shipmentItemId');
        var currentShipmentItemId = {{ $shippingItem->id }};

        console.log('testing IDS: ', progressId, shipmentItemId, currentShipmentItemId);

        if(progressId > 0 && shipmentItemId == currentShipmentItemId) {

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
</script>
@endsection