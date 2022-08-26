<?php

namespace App\Http\Controllers;

use App\Models\QualityAssurance;
use App\Models\QualityAssuranceForm;
use App\Models\QualityAssuranceFormQuestion;
use App\Models\QualityAssuranceAnswer;
use Illuminate\Http\Request;
use App\Models\ProductStockCard;
use App\Models\QualityAssurancePicture;
use App\Models\User;
use Helper;

class QualityAssuranceController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:quality-assurance.perform-QA')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:quality-assurance.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:quality-assurance.perform-QA')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:quality-assurance.reports')->only(['report']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Quality Assurance', 'View all QA list');

        $QATypes = Helper::getQATypes();
        $QACategories = Helper::getQACategories();
        $QASurveys = QualityAssurance::with(['department', 'stockCard', 'inspectionBy'])->where('is_ended', 1)->orderBy('id', 'desc')->get();

        return view('quality-assurance.index', [
            'QASurveys' => $QASurveys,
            'QATypes' => $QATypes,
            'QACategories' => $QACategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'qc_type' => 'required|integer',
            'IQCcategory' => 'required_if:qc_type,1|integer',
            'IPQCdepartment' => 'required_if:qc_type,2|exists:departments,id',
            'stock_card_id3' => 'required|exists:product_stock_cards,id',
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $qualityAssurance = new QualityAssurance;
        $qualityAssurance->qa_type = $request->qc_type;
        $qualityAssurance->qa_category = $request->qc_type == 1 ? $request->IQCcategory : null;
        $qualityAssurance->department_id = $request->qc_type == 2 ? $request->IPQCdepartment : null;
        $qualityAssurance->stock_card_id = $request->stock_card_id3;
        $qualityAssurance->qa_by = $request->user()->id;
        $qualityAssurance->save();
        $lastId = $qualityAssurance->id;

        Helper::logSystemActivity('Quality Assurance', 'Started new Quality Assurance for Stock ID: ' . $request->stock_card_id3);

        // Back to index with success
        return redirect()->route('quality-assurance.start', $lastId)->with('custom_success', 'Started new Quality Assurance Form');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Quality Assurance', 'View QA details of ID: ' . $id);

        $QA = QualityAssurance::with(['inspectionBy'])->where('is_ended', 1)->findOrFail($id);
        $QACat = $QA->qa_category;

        $productStockCard = ProductStockCard::with(['product', 'jobOrder'])->where('id', $QA->stock_card_id)->first();
        $QAForms = null;
        $guideFile = null;
        $QAFormQuestionsArr = [];
        $QAAnswers = null;
        $onSitePictures = null;

        if ($QA->qa_type < 4) {
            $QAForms = QualityAssuranceForm::where('qa_type', $QA->qa_type)
            ->when($QACat, function ($query) use ($QACat) {
                return $query->where('qa_category', $QACat);
            })
            ->get();
            foreach ($QAForms as $QAForm) {
                $QAFormQuestions = QualityAssuranceFormQuestion::where('qa_form_id', $QAForm->id)->get();
                $guideFile = $QAForm->guide_std_file;
                array_push($QAFormQuestionsArr, $QAFormQuestions);
            }

            // Previous Answers If Filled out
            $QAAnswers = QualityAssuranceAnswer::where('quality_assurance_id', $id)->get();
        }

        if ($QA->qa_type == 4) {
            $onSitePictures = QualityAssurancePicture::where('quality_assurance_id', $id)->get();
        }

        $QATypes = Helper::getQATypes();
        $QACategories = Helper::getQACategories();
        $QAOptions = Helper::getQAOptions();

        return view('quality-assurance.create', [
            'productStockCard' => $productStockCard,
            'QA' => $QA,
            'QAForms' => $QAForms,
            'QAGuideFile' => $guideFile,
            'QAFormQuestions' => $QAFormQuestionsArr,
            'QAAnswers' => $QAAnswers,
            'QATypes' => $QATypes,
            'QACategories' => $QACategories,
            'QAOptions' => $QAOptions,
            'onSitePictures' => $onSitePictures
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QualityAssurance  $qualityAssurance
     * @return \Illuminate\Http\Response
     */
    public function edit(QualityAssurance $qualityAssurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QualityAssurance  $qualityAssurance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QualityAssurance $qualityAssurance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QualityAssurance  $qualityAssurance
     * @return \Illuminate\Http\Response
     */
    public function destroy(QualityAssurance $qualityAssurance)
    {
        //
    }

    /**
     * Start QA forms process.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function start($id)
    {
        $QA = QualityAssurance::where('is_ended', 0)->findOrFail($id);
        $QACat = $QA->qa_category;

        $productStockCard = ProductStockCard::with(['product', 'jobOrder'])->where('id', $QA->stock_card_id)->first();
        $QAForms = null;
        $guideFile = null;
        $QAFormQuestionsArr = [];

        if($QA->qa_type < 4) {
            $QAForms = QualityAssuranceForm::where('qa_type', $QA->qa_type)
                ->when($QACat, function ($query) use ($QACat) {
                    return $query->where('qa_category', $QACat);
                })
                ->get();
            $QAFormQuestionsArr = [];
            foreach ($QAForms as $QAForm) {
                $QAFormQuestions = QualityAssuranceFormQuestion::where('qa_form_id', $QAForm->id)->get();
                $guideFile = $QAForm->guide_std_file;
                array_push($QAFormQuestionsArr, $QAFormQuestions);
            }
        }

        $QATypes = Helper::getQATypes();
        $QACategories = Helper::getQACategories();
        $QAOptions = Helper::getQAOptions();

        return view('quality-assurance.create', [
            'productStockCard' => $productStockCard,
            'QA' => $QA,
            'QAForms' => $QAForms,
            'QAGuideFile' => $guideFile,
            'QAFormQuestions' => $QAFormQuestionsArr,
            'QAAnswers' => null,
            'QATypes' => $QATypes,
            'QACategories' => $QACategories,
            'QAOptions' => $QAOptions
        ]);
    }

    /**
     * End QA forms process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function end(Request $request, $id)
    {
        $QA = QualityAssurance::findOrFail($id);
        $QAId = $id;

        if($QA->is_ended == 1) {
            return redirect()->route('quality-assurance.index')->with('custom_errors', 'Quality Assurance Already Done before for this Form!');
        }

        if ($QA->qa_type == 4) {
            // Validations
            $validatedData = $request->validate([
                'sample_qty' => 'required|numeric',
                'total_qty' => 'required|numeric',
                'product_photo' => 'nullable|image',
                'important_remarks' => 'nullable|string',
                'comments' => 'nullable|string',
                'onsite_photos_comments.*' => 'nullable|string',
                'onsite_photos.*' => 'nullable|image'
            ]);

            // If validations fail
            if (!$validatedData) {
                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }

            $productImagePath = null;
            if ($request->hasFile('product_photo')) {
                $file = $request->file('product_photo');
                $productImagePath = $file->store('qa_product_pictures');
            }

            // Update QA
            $QA->sample_size = $request->sample_qty;
            $QA->total_quantity = $request->total_qty;
            $QA->remarks = $request->important_remarks;
            $QA->comments = $request->comments;
            $QA->is_ended = 1;
            $QA->product_picture_link = $productImagePath;
            $QA->save();
            $lastQAId = $QA->id;

            // Save On Site QA Pictures
            $onSiteImgs = [];
            $totalOnSiteComments = count($request->onsite_photos_comments);
            for ($i = 0; $i < $totalOnSiteComments; $i++) {
                if (isset($request->file('onsite_photos')[$i])) {
                    $file = $request->file('onsite_photos')[$i];
                    $productImagePath = $file->store('qa_onsite_photos');
                    $data = [
                        'quality_assurance_id' => $lastQAId,
                        'picture_link' => $productImagePath,
                        'comments' => $request->onsite_photos_comments[$i]
                    ];
                    array_push($onSiteImgs, $data);
                }
            }
            if (!empty($onSiteImgs)) {
                QualityAssurancePicture::insert($onSiteImgs);
            }
        }

        if($QA->qa_type < 4) {
            // Validations
            $validatedData = $request->validate([
                'total_found_cr' => 'required|numeric',
                'total_found_mj' => 'required|numeric',
                'total_found_mn' => 'required|numeric',

                'total_allowed_cr' => 'required|numeric',
                'total_allowed_mj' => 'required|numeric',
                'total_allowed_mn' => 'required|numeric',

                'sample_qty' => 'required|numeric',
                'total_qty' => 'required|numeric',
                'remarks' => 'nullable|string',

                'cr.*' => 'required|numeric',
                'mj.*' => 'required|numeric',
                'mn.*' => 'required|numeric',
                'question_id.*' => 'required|exists:quality_assurance_form_questions,id',
                'answer.*' => 'required|numeric',
                'answer_remarks.*' => 'required|string'
            ]);

            // If validations fail
            if (!$validatedData) {
                return redirect()->back()
                    ->withErrors($validator)->withInput();
            }

            // Update QA
            $QA->total_defects_found_cr = $request->total_found_cr;
            $QA->total_defects_found_mj = $request->total_found_mj;
            $QA->total_defects_found_mn = $request->total_found_mn;
            $QA->total_defects_allowed_cr = $request->total_allowed_cr;
            $QA->total_defects_allowed_mj = $request->total_allowed_mj;
            $QA->total_defects_allowed_mn = $request->total_allowed_mn;
            $QA->sample_size = $request->sample_qty;
            $QA->total_quantity = $request->total_qty;
            $QA->remarks = $request->remarks;
            $QA->is_ended = 1;
            $QA->save();

            // Save QA Answers
            $totalQuestions = count($request->question_id);
            $insertAnswersDataArr = [];
            $nowTime = \Carbon\Carbon::now()->toDateTimeString();
            for ($i = 0; $i < $totalQuestions; $i++) {
                array_push($insertAnswersDataArr, [
                    'quality_assurance_id' => $QAId,
                    'qa_form_question_id' => $request->question_id[$i],
                    'answer' => $request->answer[$i],
                    'remarks' => $request->answer_remarks[$i] ?? null,
                    'cr' => $request->cr[$i] ?? null,
                    'mi' => $request->mj[$i] ?? null,
                    'mn' => $request->mn[$i] ?? null,
                    'created_at' => $nowTime,
                    'updated_at' => $nowTime
                ]);
            }
            QualityAssuranceAnswer::insert($insertAnswersDataArr);
        }

        Helper::logSystemActivity('Quality Assurance', 'Ended Quality Assurance for QA ID: ' . $QAId);

        // Back to index with success
        return redirect()->route('production.daily')->with('custom_success', 'Ended Quality Assurance Form successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        Helper::logSystemActivity('Quality Assurance', 'View Reports page');

        // This Month Date Range
        $carbonNow = \Carbon\Carbon::now();
        $thisMonthTodayDate = $carbonNow->format('Y-m-d');
        $thisMonthStartDate = $carbonNow->format('Y-m-01');

        // Filters
        $filters['dateRanges'] = [
            'start' => $request->dateStart ?? $thisMonthStartDate,
            'end' => $request->dateEnd ?? $thisMonthTodayDate
        ];
        $filters['user'] = $request->user ?? 0;
        $filters['report_type'] = $request->report_type ?? 0;
        $filters['qa_type'] = $request->qa_type ?? 1;
        $users = User::all();
        $reportItems = [];
        $QATypes = Helper::getQATypes();
        $QAOptions = Helper::getQAOptions();
        $totalMonths = (\Carbon\Carbon::createFromDate($filters['dateRanges']['start'])
            ->diff($filters['dateRanges']['end'])
            ->format('%m')) + 1;

        // Daily Report
        if ($request->isMethod('post') && $filters['report_type'] == 0) {
            $reportItems = QualityAssurance::with([
                'stockCard',
                'stockCard.product',
                'stockCard.jobOrder',
                'inspectionBy',
                'answers',
                'answers.question',
                'answers.question.form',
                ])
                ->where('is_ended', 1)
                ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                ->when(!empty($filters['user']), function ($q) use ($filters) {
                    return $q->where('qa_by', $filters['user']);
                })
                ->when(!empty($filters['qa_type']), function ($q) use ($filters) {
                    return $q->where('qa_type', $filters['qa_type']);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Monthly Report
        if ($request->isMethod('post') && $filters['report_type'] == 1) {
            $QAList = QualityAssurance::
                where('is_ended', 1)
                ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                ->when(!empty($filters['user']), function ($q) use ($filters) {
                    return $q->where('qa_by', $filters['user']);
                })
                ->when(!empty($filters['qa_type']), function ($q) use ($filters) {
                    return $q->where('qa_type', $filters['qa_type']);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $QAIds = $QAList->pluck('id')->toArray();

            $questionIdsList = QualityAssuranceAnswer::whereIn('quality_assurance_id', $QAIds)
                ->select('qa_form_question_id')
                ->groupByRaw('qa_form_question_id')
                ->pluck('qa_form_question_id')->toArray();

            $questions = QualityAssuranceFormQuestion::select('id', 'defect_category', 'question')->whereIn('id', $questionIdsList)->get();
            $questionCategories = QualityAssuranceFormQuestion::select('defect_category')->whereIn('id', $questionIdsList)->groupBy('defect_category')->pluck('defect_category')->toArray();

            $eachMonthAnswers = [];

            for ($i=0; $i < $totalMonths; $i++) {

                $dateMonth = (new \Carbon\Carbon($filters['dateRanges']['start']))
                                    ->addMonths($i)
                                    ->format("m");
                $dateYear = (new \Carbon\Carbon($filters['dateRanges']['start']))
                                    ->addMonths($i)
                                    ->format("Y");
 
                $answers = QualityAssuranceAnswer::with(['question'])
                            ->whereIn('quality_assurance_id', $QAIds)
                            ->select('id', 'qa_form_question_id', 'created_at')
                            ->selectRaw('(SUM(cr) + SUM(mi) + SUM(mn)) as total_qty')
                            ->whereRaw('MONTH(created_at) = ' . $dateMonth)
                            ->whereRaw('YEAR(created_at) = ' . $dateYear)
                            ->groupByRaw('qa_form_question_id, YEAR(created_at), MONTH(created_at)')
                            ->get();

                $totalMonthAnswer=0;
                foreach($answers as $answer) {
                    $totalMonthAnswer += $answer->total_qty;
                }

                array_push($eachMonthAnswers, [
                    'month' => $dateMonth,
                    'year' => $dateYear,
                    'answers' => $answers,
                    'answers_total' => $totalMonthAnswer
                ]);
            }

            $reportList = [
                'questions' => $questions,
                'questionCategories' => $questionCategories,
                'answers' => $eachMonthAnswers
            ];

            $reportItems = $reportList;
        }

        // Summary Report
        if ($request->isMethod('post') && $filters['report_type'] == 2) {
            $summaryItems = QualityAssurance::where('is_ended', 1)
                ->select('qa_type')
                ->selectRaw('(SUM(total_defects_found_cr) + SUM(total_defects_found_mj) + SUM(total_defects_found_mn)) as total_qty, YEAR(created_at) as qa_year, MONTH(created_at) as qa_month')
                ->whereDate('created_at', '>=', $filters['dateRanges']['start'])
                ->whereDate('created_at', '<=', $filters['dateRanges']['end'])
                ->groupByRaw('qa_type, YEAR(created_at), MONTH(created_at)')
                ->get();

            $summaryItemsFinal = [];

            foreach($summaryItems as $item) {
                $summaryItemsFinal[$item->qa_year][$item->qa_month][$item->qa_type] = $item->total_qty;
            }

            $reportItems = $summaryItemsFinal;
        }

        return view('quality-assurance.report', [
            'reportItems' => $reportItems,
            'totalMonths' => $totalMonths,
            'filters' => $filters,
            'QAOptions' => $QAOptions,
            'QATypes' => $QATypes,
            'users' => $users
        ]);
    }
}
