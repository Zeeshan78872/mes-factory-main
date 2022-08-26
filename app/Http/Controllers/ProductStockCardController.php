<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductStockCard;
use App\Models\ProductStock;
use Helper;

class ProductStockCardController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Permissions
        $this->middleware('RolePermissionCheck:stock-cards.view')->only(['index', 'show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stockCards = ProductStockCard::with(['product', 'jobOrder'])->get();
        Helper::logSystemActivity('Stock Cards', 'Stock Cards list view');
        return view('inventory.stock-cards-index', ['stockCards' => $stockCards]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Stock Cards', 'Add new stock card form open');
        return view('inventory.add-stock-cards');
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
            'ordered_quantity' => 'required|numeric',
            'product_id' => 'required|exists:products,id',
            'date_in' => 'nullable|date',
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $maxId = ProductStockCard::max('id');
        $newId = $maxId + 1;
        $productStockCard = new ProductStockCard;
        $productStockCard->stock_card_number = date("Y") . '/' . $newId;
        $productStockCard->ordered_quantity = $request->ordered_quantity;
        $productStockCard->available_quantity = $request->ordered_quantity;
        $productStockCard->product_id = $request->product_id;
        $productStockCard->date_in = $request->date_in;
        $productStockCard->save();

        // Update Product Stock
        $productStock = ProductStock::where('product_id', '=', $request->product_id)->first();
        if (empty($productStock)) {
            $productStock = new ProductStock;
            $productStock->product_id = $request->product_id;
            $productStock->quantity = $request->ordered_quantity;
        } else {
            $productStock->quantity = $productStock->quantity + $request->ordered_quantity;
        }
        $productStock->save();

        Helper::logSystemActivity('Stock Cards', 'Add new Stock Card successfully');

        // Back to index with success
        return redirect()->route('stock-cards.index')->with('custom_success', 'New Stock Card has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Stock Cards', 'View Stock Card details id: ' . $id);
        $stockCard = ProductStockCard::find($id);
        
        // If Ajax Request Return Stock Card Details
        if($request->ajax()) {
            return $stockCard;
        }

        return view('inventory.stock-cards-show', ['stockCard' => $stockCard]);
    }

    /**
     * Ajax Stock Cards by Product ID.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function ajaxStockCards($productId)
    {
        if (empty($productId)) {
            return [];
        }

        $stockCards = ProductStockCard::where('product_id', $productId)
            ->where('is_rejected', 0)
            ->where('available_quantity', '>', 0)
            ->get();

        if($stockCards->isEmpty()) {
            return [];
        }

        return $stockCards;
    }

    /**
     * Ajax Search the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxSearch(Request $request)
    {
        $search = $request->q;

        if (empty($search)) {
            return;
        }

        // Find the stockCards
        $stockCards = ProductStockCard::where('id', 'like', '%' . $search . '%')
            ->orWhere('stock_card_number', 'like', '%' . $search . '%')
            ->limit(10)->get();

        $response = [];
        foreach ($stockCards as $stockCard) {
            $response[] = array(
                "id" => $stockCard->id,
                "text" => $stockCard->id . " - " . $stockCard->stock_card_number . " [" . $stockCard->date_in . "] " . " - " . "[" . $stockCard->product->model_name . "] " . $stockCard->product->product_name . " - " . "[" . $stockCard->product->color_name . "] " . $stockCard->product->color_code
            );
        }

        return $response;
    }

    /**
     * Get Stock card by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStockCardDetails(Request $request)
    {
        $id = $request->id;
        return ProductStockCard::with(['product', 'stock'])->find($id);
    }
}
