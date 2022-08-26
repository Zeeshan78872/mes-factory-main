<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Helper;

class ProductCategoryController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Product Permissions
        $this->middleware('RolePermissionCheck:product-categories.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:product-categories.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:product-categories.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:product-categories.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Product Categories', 'View all categories list');

        $categories = ProductCategory::get();
        return view('product-categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::logSystemActivity('Product Categories', 'Open create product category form');

        return view('product-categories.create');
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
            'category_name' => 'required|string|max:255',
            'has_bom_items' => 'nullable',
            'category_code' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Store the item
        $category = new ProductCategory;
        $category->name = $request->category_name;
        $category->has_bom_items = $request->boolean('has_bom_items');
        $category->code = $request->category_code;
        $category->save();

        Helper::logSystemActivity('Product Categories', 'Added product category successfully');

        // Back to index with success
        return redirect()->route('product.categories.index')->with('custom_success', 'Product Category has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Helper::logSystemActivity('Product Categories', 'View product category details id: ' . $id);

        $category = ProductCategory::find($id);
        return view('product-categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Product Categories', 'Edit product category form opened id: ' . $id);

        $category = ProductCategory::find($id);
        return view('product-categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validations
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'has_bom_items' => 'nullable',
            'category_code' => 'required|string|max:255'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Get the item to update
        $category = ProductCategory::findOrFail($id);

        // Update the item
        $category->name = $request->category_name;
        $category->code = $request->category_code;
        $category->has_bom_items = $request->boolean('has_bom_items');
        
        $category->save();

        Helper::logSystemActivity('Product Categories', 'Edit product category successfull id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Product Category has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the item by $id
        $category = ProductCategory::findOrFail($id);
        $status = $category->delete();

        if ($status) {
            Helper::logSystemActivity('Product Categories', 'Delete product category successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'Product Category has been deleted');
        } else {
            Helper::logSystemActivity('Product Categories', 'Delete product category failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Product category was not deleted. Something went wrong.');
        }
    }
}
