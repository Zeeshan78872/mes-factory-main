@extends('layouts.app')

@section('custom_css')
<style>
    .numberInput {
        max-width: 70px;
    }

    table thead tr,
    table thead tr th,
    table tfoot tr,
    table tfoot tr td {
        background: #ddd !important;
        border: 1px solid #888 !important;
    }

    .form-check-input:disabled {
        opacity: 1 !important;
    }

    .questionAnswersCheckbox {
        min-width: 100px;
    }
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-tasks"></i> Quality Assurance Form</h1>
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
                        <h4 class="card-title d-print-none">QA FORM
                            <a href="{{ route('quality-assurance.index') }}" class="btn btn-sm btn-primary float-end ms-5"><i class="fas fa-chevron-left"></i> Go
                                Back</a>

                            @if (strpos($QAGuideFile, 'general') !== false)
                            <a href="{{ Url('') }}/{{ $QAGuideFile }}" target="_blank" class="btn btn-sm btn-secondary float-end ms-2"><i class="fas fa-file"></i> View
                                General Level STD</a>
                            @else
                            <a href="{{ Url('') }}/{{ $QAGuideFile }}" target="_blank" class="btn btn-sm btn-warning float-end ms-2"><i class="fas fa-file"></i> View
                                Special Level STD</a>
                            @endif
                        </h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method="post" action="{{ route('quality-assurance.end', $QA->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        @if ($QA->qa_type == 4)
                                        <div class="col-md-12">

                                            <div class="table-responsive mt-2">
                                                <table class="table table-striped table-hover table-bordered align-middle">
                                                    <thead>
                                                        <tr class="align-middle">
                                                            <th scope="col" style="width: 20%; font-size: 20px;" class="p-5">
                                                                <img src="{{ Url('') }}/assets/images/simewood-logo.jpeg" alt="Logo" width="300">
                                                            </th>
                                                            <th scope="col" class="text-center p-5" style="width: 40%; font-size: 20px;">
                                                                SIMEWOOD PRODUCT SDN BHD (512211-A)
                                                            </th>
                                                            <th scope="col" class="text-center p-5" style="width: 40%; font-size: 20px;">
                                                                {{ $QATypes[$QA->qa_type ?? 0] }} REPORT
                                                            </th>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Job Order:
                                                            </th>
                                                            <th scope="col">
                                                                <span class="fw-normal">{{ $productStockCard->jobOrder->order_no_manual ?? 'N/A' }}</span>
                                                            </th>
                                                            <th scope="col">
                                                                Inspection Standard: ANSI/ASQ Z1.4 <br> (formerly
                                                                known as MIL-STD-105E)
                                                            </th>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Product Code:
                                                            </th>
                                                            <th scope="col">
                                                                <span class="fw-normal">[{{ $productStockCard->product->model_name }}]
                                                                    {{ $productStockCard->product->product_name }}</span>
                                                            </th>
                                                            <th scope="col">
                                                                Sampling Plan : Single / Normal
                                                            </th>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Product Size:
                                                            </th>
                                                            <th scope="col">
                                                                <span class="fw-normal">{{ $productStockCard->product->length . 'x' . $productStockCard->product->width . 'x' . $productStockCard->product->thick }}</span>
                                                            </th>
                                                            <th scope="col">
                                                                AQL Critical: 0 , Major 2.5 & Minor 4.0
                                                            </th>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Product Color:
                                                            </th>
                                                            <th scope="col">
                                                                <span class="fw-normal">{{ $productStockCard->product->color_name . ' ' . $productStockCard->product->color_code }}</span>
                                                            </th>
                                                            <th scope="col">
                                                                Inspection Level : II
                                                            </th>
                                                        </tr>

                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Quantity:
                                                            </th>
                                                            <th scope="col">
                                                                <input type="number" name="total_qty" min="0" value="{{ $QA->total_quantity ?? $productStockCard->ordered_quantity }}" @if (!empty($onSitePictures)) disabled @endif required>
                                                            </th>
                                                            <th scope="col">
                                                                Inspection Qty: <input type="number" name="total_qty" min="0" value="{{ $QA->total_quantity ?? $productStockCard->ordered_quantity }}" @if (!empty($onSitePictures)) disabled @endif required>
                                                            </th>
                                                        </tr>

                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Inspected by:
                                                            </th>
                                                            <th scope="col">
                                                                {{ auth()->user()->name }}
                                                            </th>
                                                            <th scope="col">
                                                                @if (strpos($QAGuideFile, 'general') !== false)
                                                                @php
                                                                $sampleSize = Helper::getQAGeneralSTDSample($productStockCard->ordered_quantity);
                                                                @endphp
                                                                @else
                                                                @php
                                                                $sampleSize = Helper::getQASpecialSTDSample($productStockCard->ordered_quantity);
                                                                @endphp
                                                                @endif
                                                                Sample Size: <input type="number" id="sample_qty" name="sample_qty" min="0" value="{{ $QA->sample_size ?? $sampleSize }}" @if (!empty($onSitePictures)) disabled @endif required>
                                                            </th>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Product Description:
                                                            </th>
                                                            <th scope="col" colspan="2">
                                                                {!! $productStockCard->product->item_description !!}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-7" style="border: 1px solid #333;">
                                                    <h3>IMPORTANT REMARKS / GENERAL DEFECTIVES:</h3>
                                                    @if (!empty($onSitePictures))
                                                    {{ $QA->comments }}
                                                    @else
                                                    <textarea name="important_remarks" id="important_remarks" class="form-control" rows="6" @if (!empty($onSitePictures)) disabled @endif></textarea>
                                                    @endif
                                                    <br>
                                                    <hr>
                                                    <h3>Comments:</h3>
                                                    @if (!empty($onSitePictures))
                                                    {{ $QA->comments }}
                                                    @else
                                                    <textarea name="comments" id="comments" class="form-control" rows="6"></textarea>
                                                    @endif

                                                    <br><br>
                                                </div>
                                                <div class="col-md-5 text-center" style="border: 1px solid #333;">
                                                    <h3>PRODUCT PHOTO:</h3>
                                                    @if (!empty($onSitePictures))
                                                    <a href="{{ Url('') }}/uploads/{{ $QA->product_picture_link }}" target="_blank">
                                                        <img src="{{ Url('') }}/uploads/{{ $QA->product_picture_link }}" class="img-responsive img-thumbnail" style="max-height: 300px" />
                                                    </a>
                                                    @else
                                                    <input class="form-control" type="file" id="product_photo" name="product_photo" accept="image/*">
                                                    @endif
                                                    <br><br>
                                                </div>

                                                <div class="col-md-12 text-center" style="border: 1px solid #333;">
                                                    <h3>ON SITE CHECKING PHOTOS:</h3>

                                                    <style>
                                                        .col-print-3 {width:25%; float:left;}
                                                    </style>

                                                    @if(!empty($onSitePictures))
                                                        @foreach($onSitePictures->chunk(4) as $onSitePicturesChunk)
                                                        <div class="row">
                                                            @foreach($onSitePicturesChunk as $onSite)
                                                            <div class="col-md-3 col-print-3">
                                                                <a href="{{ Url('') }}/uploads/{{ $onSite->picture_link }}" target="_blank">
                                                                    <img src="{{ Url('') }}/uploads/{{ $onSite->picture_link }}" class="img-responsive img-thumbnail" style="max-height: 300px" />
                                                                </a>
                                                                <p>
                                                                    {{ $onSite->comments }}
                                                                </p>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        @endforeach
                                                    @endif


                                                    @if(empty($onSitePictures))
                                                    <div class="table-responsive onSiteList">
                                                        <table class="table table-striped table-hover table-bordered align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" style="width: 400px;">Image</th>
                                                                    <th scope="col" class="text-start">Comments
                                                                    </th>
                                                                    <th scope="col" style="width: 100px;" class="d-print-none">Action</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <input class="form-control" type="file" id="onsite_photos" name="onsite_photos[]" accept="image/*">
                                                                    </td>
                                                                    <td>
                                                                        <textarea name="onsite_photos_comments[]" id="onsite_photos_comments" class="form-control" rows="6"></textarea>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="5">
                                                                        <button type="button" class="btn btn-sm btn-primary addMoreOnSite"><i class="fas fa-plus"></i> Add
                                                                            More</button>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    @endif

                                                    <br><br>
                                                </div>
                                            </div>

                                        </div>
                                        @endif

                                        @if ($QA->qa_type < 4) <div class="col-md-12">

                                            <div class="table-responsive mt-2">
                                                <table class="table table-striped table-hover table-bordered align-middle">
                                                    <thead>
                                                        <tr class="align-middle">
                                                            <th scope="col" style="width: 33.33%;">
                                                                <img src="{{ Url('') }}/assets/images/simewood-logo.jpeg" alt="Logo" width="300">
                                                            </th>
                                                            <th scope="col" colspan="3" class="text-center" style="width: 33.33%;">
                                                                SIMEWOOD PRODUCT SDN BHD (512211-A)
                                                            </th>
                                                            <th scope="col" class="text-center" style="width: 33.33%;">
                                                                {{ $QATypes[$QA->qa_type ?? 0] }} REPORT
                                                                <br>
                                                                Inspection Standard: ANSI/ASQ Z1.4
                                                            </th>
                                                        </tr>
                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Job Order: <span class="fw-normal">{{ $productStockCard->jobOrder->order_no_manual ?? 'N/A' }}</span>
                                                            </th>
                                                            <th scope="col" colspan="3" class="text-center">
                                                                Sampling Plan : <span class="fw-normal">Single
                                                                    / Normal</span>
                                                            </th>
                                                            <th scope="col">
                                                                Category: <span class="fw-normal">{{ $QACategories[$QA->qa_category ?? 0] }}</span>
                                                            </th>
                                                        </tr>

                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Product Code: <span class="fw-normal">[{{ $productStockCard->product->model_name }}]
                                                                    {{ $productStockCard->product->product_name }}</span>
                                                            </th>
                                                            <th scope="col" colspan="3" class="text-center">
                                                                AQL Critical: 0 , Major 2.5 & Minor 4.0
                                                            </th>
                                                            <th scope="col">
                                                                Total Quantity: <input type="number" name="total_qty" min="0" value="{{ $QA->total_quantity ?? $productStockCard->ordered_quantity }}" @if (!empty($QAAnswers)) disabled @endif readonly>
                                                            </th>
                                                        </tr>

                                                        <tr class="align-middle">
                                                            <th scope="col">
                                                                Po Number: <span class="fw-normal">{{ $productStockCard->jobOrder->po_no ?? 'N/A' }}</span>
                                                            </th>
                                                            <th scope="col" colspan="3" class="text-center">
                                                                Inspection Level: <span class="fw-normal">ll</span>
                                                            </th>
                                                            <th scope="col">
                                                                @if (strpos($QAGuideFile, 'general') !== false)
                                                                @php
                                                                $sampleSize = Helper::getQAGeneralSTDSample($productStockCard->ordered_quantity);
                                                                @endphp
                                                                @else
                                                                @php
                                                                $sampleSize = Helper::getQASpecialSTDSample($productStockCard->ordered_quantity);
                                                                @endphp
                                                                @endif
                                                                Sample Size: <input type="number" name="sample_qty" min="0" id="sample_qty" value="{{ $QA->sample_size ?? $sampleSize }}" @if (!empty($QAAnswers)) disabled @endif>
                                                            </th>
                                                        </tr>

                                                        <tr class="align-middle">
                                                            <th scope="col" rowspan="2">General Checklist</th>
                                                            <th scope="col" colspan="3" class="text-center">
                                                                Defect found</th>
                                                            <th scope="col" rowspan="2" class="text-center">
                                                                Actions after inspection</th>
                                                        </tr>
                                                        <tr class="align-middle text-center fw-bold">
                                                            <td scope="col">Cr</td>
                                                            <td scope="col">Mj</td>
                                                            <td scope="col">Mn</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $counter = 0;
                                                        @endphp

                                                        @foreach ($QAFormQuestions as $QAQuestionForm)
                                                        @foreach ($QAQuestionForm as $QAQuestion)
                                                        @php
                                                        $answerCr = null;
                                                        $answerMj = null;
                                                        $answerMn = null;
                                                        $selectedAnswer = null;
                                                        $answerRemarks = null;
                                                        @endphp

                                                        {{-- If Answers exists --}}
                                                        @if (!empty($QAAnswers))
                                                        @foreach ($QAAnswers as $QAAnswer)
                                                        @if ($QAAnswer->qa_form_question_id == $QAQuestion->id)
                                                        @php
                                                        $answerCr = $QAAnswer->cr;
                                                        $answerMj = $QAAnswer->mi;
                                                        $answerMn = $QAAnswer->mn;
                                                        $selectedAnswer = $QAAnswer->answer;
                                                        $answerRemarks = $QAAnswer->remarks;
                                                        @endphp
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                        <tr>
                                                            <td class="fw-bold">
                                                                <input type="hidden" name="question_id[{{ $counter }}]" value="{{ $QAQuestion->id }}" @if (!empty($QAAnswers)) disabled @endif>
                                                                {{ $QAQuestion->question }}
                                                            </td>
                                                            @if (!$QAQuestion->is_remarks)
                                                            <td class="text-center">
                                                                <input type="number" name="cr[{{ $counter }}]" onkeyup="crKeyup(this)" step=".01" min="0" value="{{ $answerCr === null ? '0' : str_replace('.00', '', $answerCr) }}" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" name="mj[{{ $counter }}]" onkeyup="mjKeyup(this)" step=".01" min="0" value="{{ $answerMj === null ? '0' : str_replace('.00', '', $answerMj) }}" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                                
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" name="mn[{{ $counter }}]" step=".01" onkeyup="mnKeyup(this)" min="0" value="{{ $answerMn === null ? '0' : str_replace('.00', '', $answerMn) }}" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                                
                                                            </td>
                                                            @else
                                                            <td class="text-center" colspan="3">
                                                                Remarks:
                                                                <input type="text" value="{{ $answerRemarks === null ? '' : $answerRemarks }}" name="answer_remarks[{{ $counter }}]" style="width: 100%" @if (!empty($QAAnswers)) disabled @endif>
                                                            </td>
                                                            @endif
                                                            <td>
                                                                @foreach ($QAOptions as $QAOption)
                                                                @if ($loop->index === 0 || ($loop->index > 2 && $QAQuestion->is_remarks) || ($loop->index === 5 && $QA->qa_type > 1))
                                                                @php
                                                                continue;
                                                                @endphp
                                                                @endif
                                                                <label class="form-check-label questionAnswersCheckbox">
                                                                    <input class="form-check-input" type="radio" name="answer[{{ $counter }}]" value="{{ $loop->index }}" @if ($loop->index == 1 && empty($QAAnswers)) checked @endif
                                                                    @if (!empty($QAAnswers)) disabled @endif
                                                                    @if ($selectedAnswer == $loop->index) checked @endif>
                                                                    {{ $QAOption }}
                                                                </label>
                                                                @endforeach

                                                            </td>
                                                        </tr>
                                                        @php
                                                        $counter++;
                                                        @endphp
                                                        @endforeach
                                                        @endforeach

                                                    </tbody>

                                                    <tfoot>

                                                        <tr>
                                                            <td class="fw-bold">
                                                                TOTAL DEFECT FOUND
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" name="total_found_cr" id="total_found_cr" step=".01" value="{{ $QA->total_defects_found_cr ?? '0' }}" min="0" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                                 <input type="hidden" id="cr" name="">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" name="total_found_mj" id="total_found_mj" step=".01" value="{{ $QA->total_defects_found_mj ?? '0' }}" min="0" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                                <input type="hidden" id="mj" name="">
                                                                 
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" name="total_found_mn" id="total_found_mn" step=".01" value="{{ $QA->total_defects_found_mn ?? '0' }}" min="0" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                                <input type="hidden" id="mn" name="">
                                                               
                                                            </td>
                                                            <td>
                                                                <b>Inspected By</b>:
                                                                {{ $QA->inspectionBy->name ?? auth()->user()->name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold">
                                                                TOTAL DEFECT ALLOWED
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" id="total_allowed_cr" name="total_allowed_cr" step=".01" value="{{ $QA->total_defects_allowed_cr ?? '0' }}" min="0" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" id="total_allowed_mj" name="total_allowed_mj" step=".01" value="{{ $QA->total_defects_allowed_mj ?? '0' }}" min="0" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="number" id="total_allowed_mn" name="total_allowed_mn" step=".01" value="{{ $QA->total_defects_allowed_mn ?? '0' }}" min="0" class="numberInput" @if (!empty($QAAnswers)) disabled @endif>
                                                            </td>
                                                            <td>
                                                                <b>Date:</b>
                                                                {{ $QA->created_at ?? date('Y-m-d H:i:s') }}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-bold">
                                                                Notes:
                                                                <ul class="list-unstyled">
                                                                    <li>
                                                                        * Cr - can cause death
                                                                    </li>
                                                                    <li>
                                                                        * Mj - can cause serious injury
                                                                    </li>
                                                                    <li>
                                                                        * Mn - can cause minor injury
                                                                    </li>
                                                                    <li>
                                                                        * If total defect found > total defecct
                                                                        allowed, need to inspect total quantity.
                                                                    </li>
                                                                </ul>
                                                            </td>

                                                            <td colspan="4" class="align-top text-center fw-bold">
                                                                Remarks/Comments:

                                                                <textarea name="remarks" class="form-control" rows="4" maxlength="250" @if (!empty($QAAnswers)) disabled @endif>{{ $QA->remarks === null ? '' : $QA->remarks }}</textarea>
                                                            </td>
                                                        </tr>

                                                    </tfoot>
                                                </table>
                                            </div>

                                    </div>
                                    @endif

                                    @if (empty($QAAnswers) && empty($onSitePictures))
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save"></i> Submit</button>
                                    </div>
                                    @endif
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
    function crKeyup(ele) {
        var sum =0 ;
        var val = Number($(ele).val());
        console.log(val);
        var tcr= Number($('#cr').val());
        if(tcr != 0){
            sum = tcr;
        }
        sum += val;
        $('#cr').val(sum);
        $('#total_found_cr').val(sum);

    }
    function mjKeyup(ele) {
        var sum =0 ;
        var val = Number($(ele).val());
        console.log(val);
        var tcr= Number($('#mj').val());
        if(tcr != 0){
            sum = tcr;
        }
        sum += val;
        $('#mj').val(sum);
        $('#total_found_mj').val(sum);

    }function mnKeyup(ele) {
        var sum =0 ;
        var val = Number($(ele).val());
        console.log(val);
        var tcr= Number($('#mn').val());
        if(tcr != 0){
            sum = tcr;
        }
        sum += val;
        $('#mn').val(sum);
        $('#total_found_mn').val(sum);

    }
    $(document).on('keyup', '#sample_qty', function() {
        var simple_size = $(this).val();
        if (simple_size >= 2 && simple_size <= 50) {
            $("#total_allowed_cr").val(0);
            $("#total_allowed_mj").val(0);
            $("#total_allowed_mn").val(0);
        } else if (simple_size >= 51 && simple_size <= 90) {
            $("#total_allowed_cr").val(0);
            $("#total_allowed_mj").val(0);
            $("#total_allowed_mn").val(1);
        } else if (simple_size >= 91 && simple_size <= 150) {
            $("#total_allowed_cr").val(0);
            $("#total_allowed_mj").val(1);
            $("#total_allowed_mn").val(2);
        } else if (simple_size >= 151 && simple_size <= 280) {
            $("#total_allowed_cr").val(0);
            $("#total_allowed_mj").val(2);
            $("#total_allowed_mn").val(3);
        } else if (simple_size >= 281 && simple_size <= 500) {
            $("#total_allowed_cr").val(0);
            $("#total_allowed_mj").val(3);
            $("#total_allowed_mn").val(5);
        } else if (simple_size >= 501 && simple_size <= 1200) {
            $("#total_allowed_cr").val(0);
            $("#total_allowed_mj").val(5);
            $("#total_allowed_mn").val(7);
        } else if (simple_size >= 1201 && simple_size <= 3200) {
            $("#total_allowed_cr").val(0);
            $("#total_allowed_mj").val(7);
            $("#total_allowed_mn").val(10);
        } else if (simple_size >= 3201 && simple_size <= 10000) {
            $("#total_allowed_cr").val(0)
            $("#total_allowed_mj").val(10);
            $("#total_allowed_mn").val(14);
        } else if (simple_size >= 10001 && simple_size <= 35000) {
            $("#total_allowed_cr").val(0)
            $("#total_allowed_mj").val(14);
            $("#total_allowed_mn").val(21);
        } else if (simple_size >= 35001 && simple_size <= 150000) {
            $("#total_allowed_cr").val(0)
            $("#total_allowed_mj").val(21);
            $("#total_allowed_mn").val(21);
        } else if (simple_size >= 150001 && simple_size <= 500000) {
            $("#total_allowed_cr").val(0)
            $("#total_allowed_mj").val(21);
            $("#total_allowed_mn").val(21);
        } else {
            $("#total_allowed_cr").val(0)
            $("#total_allowed_mj").val(21);
            $("#total_allowed_mn").val(21);
        }
    });
    @if($QA->qa_type == 4)
    $(".addMoreOnSite").click(function() {
        var trBody = `
            <tr>
                <td>
                    <input class="form-control" type="file" id="onsite_photos" name="onsite_photos[]" accept="image/*">
                </td>
                <td>
                    <textarea name="onsite_photos_comments[]" id="onsite_photos_comments" class="form-control" rows="6"></textarea>
                </td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger removeOnSiteBtn"><i
                            class="far fa-trash-alt"></i></a>
                </td>
            </tr>
            `;
        $(".onSiteList tbody").append(trBody);
    });

    $(document).on('click', '.removeOnSiteBtn', function() {
        if (confirm('Are you sure?')) {
            $(this).parent().parent().remove();
        }
    });
    @endif
</script>
@endsection