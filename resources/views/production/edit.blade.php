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
                                    <li>Work Status: <b>{{ $productionItem->work_status === 1 ? 'New Item' : 'Rework Item' }}</b></li>
                                    <li>
                                        Stock Cards: 
                                        @if(!$productionItem->stockCards->isEmpty())
                                            @foreach ($productionItem->stockCards as $stockCard)
                                            <b>{{ $stockCard->stockCard->stock_card_number }}</b>,
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
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                @if($productionItem->department->is_chemical)
                                <div class="col-md-12">
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
                                                @if(!$productionItem->chemicals->isEmpty())
                                                    @foreach ($productionItem->chemicals as $chemical)
                                                    <tr>
                                                        <td>
                                                            [{{ $chemical->chemical->stock_card_number }}] {{ $chemical->chemical->product->product_name }} {{ $chemical->chemical->product->model_name }}
                                                        </td>
                                                        <td>
                                                            {{ $chemical->chemical->product->material }}
                                                        </td>
                                                        <td>
                                                            {{ $chemical->quantity }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ Url('') }}/productions/delete-progress-chemical/{{ $chemical->id }}" class="btn btn-danger btn-sm deleteChemical" onclick="return confirm('Are you sure?')">Delete</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                
                                </div>
                                @endif
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
<script src="{{ url('/assets') }}/plugins/select2/js/select2.full.min.js"></script>
<script>
    function getProductionProgresses(firstTime=0) {
        var htmlBody = '';
        var lastTimerType = 1;

        $.ajax({
            url: "{{ route('production.daily.process.progresses', $productionItem->id) }}",
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
                            <td>${ progress.timer_type === 1 ? 'Production' : 'Break' } </td>
                            <td>${ progress.started_at }</td>
                            <td>${ progress.ended_at ?? 'Not Stopped Yet!'}</td>
                            <td>${ timeReadable }</td>
                            <td><a href="{{ Url('') }}/productions/delete-progress/${ progress.id }" class="btn btn-danger btn-sm deleteProgress" onclick="return confirm('Are you sure?')">Delete</a></td>
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
            },
            error: (jqXHR, textStatus, errorThrown) => {
                "Error: " + jqXHR.status;
            }
        })
    }

    // Get All Progress Lists
    getProductionProgresses(1);
</script>
@endsection