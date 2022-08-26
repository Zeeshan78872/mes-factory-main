<?php

namespace App\Http\Controllers;

use App\Imports\BomImport;
use App\Models\Product;
use App\Models\ProductBomMapping;
use App\Models\ProductPicture;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use App\Models\Color;
use App\Models\Material;
use App\Models\JobOrderBomList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Helper;
use Excel;
use Maatwebsite\Excel\Concerns\ToArray;

class ProductController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Check Product Permissions
        $this->middleware('RolePermissionCheck:products.add')->only(['create', 'store']);
        $this->middleware('RolePermissionCheck:products.view')->only(['index', 'show']);
        $this->middleware('RolePermissionCheck:products.edit')->only(['edit', 'update']);
        $this->middleware('RolePermissionCheck:products.delete')->only(['destroy']);
        $this->middleware('RolePermissionCheck:products.map-bom-list')->only(['bomMapping', 'storeBomMapping', 'storeBomMappingUploadFile']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::logSystemActivity('Products', 'View all products list');

        $products = Product::with(['category', 'subCategory', 'bomCategory', 'parentProduct'])->groupBy('model_name')->get();
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Helper::logSystemActivity('Products', 'Open create product form');
        $categories = ProductCategory::get();
        $units = ProductUnit::get();
        $colors = Color::get();
        $materials = Material::get();

        $preFillType = $request->preFillType;
        $productId = $request->productId;

        if(!empty($productId) && !empty($preFillType)) {
            // Real Product
            $product = Product::find($productId);
        } else {
            // Dummy Object
            $product = (object) [
                "price_per_unit" => null,
                "model_name" => null,
                "item_description" => null,
                "color_name" => null,
                "color_code" => null,
                "length" => null,
                "length_unit" => null,
                "width" => null,
                "width_unit" => null,
                "thick" => null,
                "thick_unit" => null,
                "category_id" => null,
                "subcategory_id" => null,
                "bomcategory_id" => null,
                'product_name' => null,
                'pdf_url' => null,
                'material' => null
            ];
        }

        return view('products.create', [
            'categories' => $categories,
            'units' => $units,
            'preFillType' => $preFillType,
            'product' => $product,
            'colors' => $colors,
            'materials' => $materials
            ]);
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
            'parent_product_id' => 'nullable|exists:products,id',
            'price_per_unit' => 'nullable|regex:/^\d{1,13}(\.\d{1,4})?$/',
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_categories,id',
            'bomcategory_id' => 'nullable|exists:product_categories,id',
            'model_name' => 'required|string|max:255',
            'product_name' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'color.*' => 'nullable|string|max:255',
            'length.*' => 'nullable|string|max:255',
            'length_unit.*' => 'nullable|string|max:255',
            'width.*' => 'nullable|string|max:255',
            'width_unit.*' => 'nullable|string|max:255',
            'thick.*' => 'nullable|string|max:255',
            'thick_unit.*' => 'nullable|string|max:255',
            'item_description' => 'nullable|string',
            'pictures.*.*' => 'nullable|image',
            'pdf.*' => 'nullable|mimes:pdf',
            'image' => 'nullable|image'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Total Product Variants submitted
        $totalVariants = count($request->color);
        $productIds = [];

        for($i=0; $i < $totalVariants; $i++) {
            // Upload PDF
            $PDFPath = null;
            if (isset($request->file('pdf')[$i]) && !empty(isset($request->file('pdf')[$i]))) {
                $file = $request->file('pdf')[$i];
                $PDFPath = $file->store('pdf');
            }

            $color = $request->color[$i];
            $colors = explode('_', $color);
            $color_name = $colors[0] ?? null;
            $color_code = $colors[1] ?? null;
            $length = $request->length[$i];
            $length_unit = $request->length_unit[$i];
            $width = $request->width[$i];
            $width_unit = $request->width_unit[$i];
            $thick = $request->thick[$i];
            $thick_unit = $request->thick_unit[$i];

            // Store the item
            $product = new Product;
            $product->category_id = $request->category_id;
            $product->parent_id = $request->parent_product_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->bomcategory_id = $request->bomcategory_id;
            $product->price_per_unit = $request->price_per_unit;
            $product->model_name = $request->model_name;
            $product->product_name = $request->product_name;
            $product->material = $request->material;

            $product->color_name = $color_name;
            $product->color_code = $color_code;

            $product->length = $length;
            $product->length_unit = $length_unit;
            $product->width = $width;
            $product->width_unit = $width_unit;
            $product->thick = $thick;
            $product->thick_unit = $thick_unit;

            $product->item_description = $request->item_description;
            $product->pdf_url = $PDFPath;
            if (!empty($request->file('image'))) {
                $file = $request->file('image');
                $imagePath = $file->store('image');
                $product->image = $imagePath;
            }
            $product->save();

            $newProductId = $product->id;

            // Each Variant Picture
            $productImgs = [];
            if (isset($request->file('pictures')[$i])) {
                $files = $request->file('pictures')[$i];

                foreach ($files as $file) {
                    $productImagePath = $file->store('pictures');
                    $data = [
                        'picture_link' => $productImagePath,
                        'product_id' => $newProductId
                    ];
                    array_push($productImgs, $data);
                }
            }
            if(!empty($productImgs)) {
                ProductPicture::insert($productImgs);
            }

            // Map BOM Mappings If exists
            $oldProductId = Product::where('model_name', $product->model_name)->orderBy('id', 'asc')->pluck('id')->first();
            $oldBomItems = ProductBomMapping::where('product_id', $oldProductId)->get();
            $bomItems = [];
            $nowTime = \Carbon\Carbon::now()->toDateTimeString();
            foreach($oldBomItems as $bomItem) {
                $item_id = $bomItem->item_id;
                $quantity = $bomItem->quantity;
                $data = [
                    'product_id' => $newProductId,
                    'item_id' => $item_id,
                    'quantity' => $quantity,
                    'created_at' => $nowTime,
                    'updated_at' => $nowTime
                ];
                array_push($bomItems, $data);

            }
            if(!empty($bomItems)) {
                // Save New Mappings
                ProductBomMapping::insert($bomItems);
            }
        }

        Helper::logSystemActivity('Products', 'Added product successfully');

        // Back to index with success
        return redirect()->route('products.index')->with('custom_success', 'Product has been added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show1($id, $order_id, Request $request)
    {

        $product=DB::select('select s1.id,s1.parent_id,s1.model_name,s1.product_name,s1.material,s1.has_bom_items,s1.category_id,s1.name,s1.product_parent_id,s1.parent_product_model_name,s1.parent_product_product_name,s1.length_unit, s1.thick_unit, s1.width_unit,s1.length, s1.thick, s1.width, s2.order_length_unit, s2.order_thick_unit, s2.order_length, s2.order_thick, s2.order_width  from (select `products`.`id`, `products`.`parent_id`, `products`.`model_name`, `products`.`product_name`, `products`.`material`, `product_categories`.`has_bom_items`, `products`.`category_id`, `product_categories`.`name`, `parent`.`id` as `product_parent_id`, `parent`.`model_name` as `parent_product_model_name`, `parent`.`product_name` as `parent_product_product_name`, `products`.`length_unit`, `products`.`thick_unit`, `products`.`width_unit`, `products`.`length`, `products`.`thick`, `products`.`width` from `products` inner join `product_categories` on `products`.`category_id` = `product_categories`.`id` left join `products` as `parent` on `products`.`parent_id` = `parent`.`id` where `products`.`id` = '.$id.') as s1 left join ( select * from job_order_bom_lists where item_id = '.$id.' and order_id ='.$order_id.') as s2 on s2.item_id = s1.id');


        // $product=Product::select('1products.id','products.parent_id','products.model_name','products.product_name','products.material','product_categories.has_bom_items','products.category_id','product_categories.name','parent.id as proudct_parent_id','parent.model_name as parent_product_model_name','parent.product_name as parent_product_product_name','products.length_unit','products.thick_unit','products.width_unit','products.length','products.thick','products.width','job_order_bom_lists.order_length','job_order_bom_lists.order_length_unit','job_order_bom_lists.order_width','job_order_bom_lists.order_width_unit','job_order_bom_lists.order_thick','job_order_bom_lists.order_thick_unit')
        // ->join('product_categories', 'products.category_id', '=', 'product_categories.id')
        // ->leftJoin('products as parent','products.parent_id','=','parent.id')
        // ->leftJoin('job_order_bom_lists','products.id','=','job_order_bom_lists.item_id')
        // ->where('products.id',$id)->where('order_id',$order_id)
        // ->get();
         return $product;
    }
    public function show($id, Request $request)
    {
        Helper::logSystemActivity('Products', 'View product details id: ' . $id);

        $categories = ProductCategory::get();
        $units = ProductUnit::get();
        $colors = Color::get();
        $materials = Material::get();

        $product = Product::with(['packing.pictures', 'category', 'subCategory', 'bomCategory', 'productPictures', 'parentProduct'])->find($id);
        $productVariants = Product::with(['productPictures'])->where('model_name', $product->model_name)->get();

        // If Ajax Request Return Product Details
        if($request->ajax()) {
            return $product;
        }

        return view('products.show', [
            'product' => $product,
            'categories' => $categories,
            'colors' => $colors,
            'materials' => $materials,
            'units' => $units,
            'productVariants' => $productVariants
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Helper::logSystemActivity('Products', 'Edit product form opened id: ' . $id);

        $categories = ProductCategory::get();
        $units = ProductUnit::get();
        $colors = Color::get();
        $materials = Material::get();

        $product = Product::with(['category', 'subCategory', 'productPictures', 'parentProduct'])->find($id);
        $productVariants = Product::with(['productPictures'])->where('model_name', $product->model_name)->get();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
            'units' => $units,
            'colors' => $colors,
            'materials' => $materials,
            'productVariants' => $productVariants
        ]);
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
            'parent_product_id' => 'nullable|exists:products,id',
            'price_per_unit' => 'nullable|regex:/^\d{1,13}(\.\d{1,4})?$/',
            'category_id' => 'nullable|exists:product_categories,id',
            'subcategory_id' => 'nullable|exists:product_categories,id',
            'bomcategory_id' => 'nullable|exists:product_categories,id',
            'model_name' => 'required|string|max:255',
            'material' => 'nullable|string|max:255',
            'product_name.*' => 'nullable|string|max:255',
            'color.*' => 'nullable|string|max:255',
            'length.*' => 'nullable|string|max:255',
            'length_unit.*' => 'nullable|string|max:255',
            'width.*' => 'nullable|string|max:255',
            'width_unit.*' => 'nullable|string|max:255',
            'thick.*' => 'nullable|string|max:255',
            'thick_unit.*' => 'nullable|string|max:255',
            'item_description' => 'nullable|string',
            'pictures.*.*' => 'nullable|image',
            'pdf.*' => 'nullable|mimes:pdf',
            'image' => 'nullable|image',

        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        // Total Product Variants submitted
        $totalVariants = count($request->product_ids);

        for ($i = 0; $i < $totalVariants; $i++) {
            $PDFPath = null;
            if (isset($request->file('pdf')[$i]) && !empty(isset($request->file('pdf')[$i]))) {
                $file = $request->file('pdf')[$i];
                $PDFPath = $file->store('pdf');
            }

            $productId = $request->product_ids[$i];
            $color = $request->color[$i];
            $colors = explode('_', $color);
            $color_name = $colors[0] ?? null;
            $color_code = $colors[1] ?? null;
            $length = $request->length[$i];
            $length_unit = $request->length_unit[$i];
            $width = $request->width[$i];
            $width_unit = $request->width_unit[$i];
            $thick = $request->thick[$i];
            $thick_unit = $request->thick_unit[$i];
            $product_name = $request->product_name[$i];

            // Store the item
            $product = Product::find($productId);
            $product->category_id = $request->category_id;
            $product->parent_id = $request->parent_product_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->bomcategory_id = $request->bomcategory_id;
            $product->price_per_unit = $request->price_per_unit;
            $product->model_name = $request->model_name;
            $product->product_name = $product_name;
            $product->material = $request->material;

            $product->color_name = $color_name;
            $product->color_code = $color_code;

            $product->length = $length;
            $product->length_unit = $length_unit;
            $product->width = $width;
            $product->width_unit = $width_unit;
            $product->thick = $thick;
            $product->thick_unit = $thick_unit;

            $product->item_description = $request->item_description;
            if(!empty($PDFPath)) {
                $product->pdf_url = $PDFPath;
            }
            if (!empty($request->file('image'))) {
                $file = $request->file('image');
                $imagePath = $file->store('image');
                $product->image = $imagePath;
            }
            $product->save();

            // Each Variant Picture
            $productImgs = [];
            if (isset($request->file('pictures')[$i])) {
                $files = $request->file('pictures')[$i];

                foreach ($files as $file) {
                    $productImagePath = $file->store('pictures');
                    $data = [
                        'picture_link' => $productImagePath,
                        'product_id' => $productId
                    ];
                    array_push($productImgs, $data);
                }
            }
            if (!empty($productImgs)) {
                ProductPicture::insert($productImgs);
            }
        }

        Helper::logSystemActivity('Products', 'Edit product successfull id: ' . $id);

        // Back to index with success
        return back()->with('custom_success', 'Product has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id == 18) {
            return back()->with('custom_errors', 'Product was not deleted. This product is hard coded in system. You can not delete this.');
        }
        // Find the product by $id
        $product = Product::findOrFail($id);

        // Delete Child
        ProductPicture::where('product_id', $id)->delete();

        // delete Product
        $status = $product->delete();

        if ($status) {
            Helper::logSystemActivity('Products', 'Delete product successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'Product has been deleted');
        } else {
            Helper::logSystemActivity('Products', 'Delete product failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Product was not deleted. Something went wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePicture($id)
    {
        // Find the picture by $id
        $picture = ProductPicture::findOrFail($id);

        // delete Product
        Storage::delete($picture->picture_link);
        $status = $picture->delete();

        if ($status) {
            Helper::logSystemActivity('Products', 'Delete product picture successfull id: ' . $id);

            // If success
            return back()->with('custom_success', 'Product picture has been deleted');
        } else {
            Helper::logSystemActivity('Products', 'Delete product picture failed id: ' . $id);

            // If no success
            return back()->with('custom_errors', 'Product picture was not deleted. Something went wrong.');
        }
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

        // Find the products
        $products = Product::where('model_name', 'like', '%' . $search . '%')
            ->orWhere('product_name', 'like', '%' . $search . '%')
            ->orWhere('color_name', 'like', '%' . $search . '%')
            ->orWhere('color_code', 'like', '%' . $search . '%')
            ->limit(100)->get();

        $response = [];
        foreach ($products as $product) {
            $response[] = array(
                "id" => $product->id,
                "data-mobile" => "dnsj",
                "text" => $product->model_name . " " . $product->product_name . " " . $product->color_name . " [" . $product->color_code . "] " . $product->length ."x". $product->width ."x". $product->thick
            );
        }

        return $response;
    }

    /**
     * Show the form for Mapping BOM of product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bomMapping($id, Request $request)
    {
        Helper::logSystemActivity('Products', 'Open BOM Mapping for product id: ' . $id);

        // Find the product by $id
        $product = Product::findOrFail($id);

        // Get Product BOM List In case the Job Order BOM Is not created Yet
        $bomList = ProductBomMapping::with(
            [
                'item',
                'item.category',
                'item.subCategory',
                'item.bomCategory',
                'item.parentProduct',
                'product',
                'product.category',
                'product.subCategory',
                'product.bomCategory',
                'product.parentProduct'
            ]
        )->where('product_id', $id)->get()->sortByDesc('item.bomCategory.id');

        // If Ajax Request Return Details
        if ($request->ajax()) {
            return $bomList;
        }

        $categories = ProductCategory::get();
        $units = ProductUnit::get();

        return view('bom-list.product-mapping', [
            'product' => $product,
            'categories' => $categories,
            'units' => $units,
            'bomList' => $bomList
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeBomMapping($id, Request $request)
    {
        // Validations
        $validatedData = $request->validate([
            'item_id.*' => 'required|exists:products,id',
            'length.*' => 'nullable|numeric',
            'width.*' => 'nullable|numeric',
            'thick.*' => 'nullable|numeric',
            'length_unit.*' => 'nullable|string',
            'width_unit.*' => 'nullable|string',
            'thick_unit.*' => 'nullable|string',
            'quantity.*' => 'required|numeric'
        ]);

        // If validations fail
        if (!$validatedData) {
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        $product = Product::findOrFail($id);

        $productIds = Product::where('model_name', $product->model_name)->pluck('id')->toArray();

        // Total Items submitted
        $totalItems = count($request->item_id);

        $nowTime = \Carbon\Carbon::now()->toDateTimeString();

        foreach($productIds as $productId) {
            $bomItems = [];
            for ($i = 0; $i < $totalItems; $i++) {
                $item_id = $request->item_id[$i];
                $quantity = $request->quantity[$i];
                $length = $request->length[$i];
                $width = $request->width[$i];
                $thick = $request->thick[$i];

                $length_unit = $request->length_unit[$i];
                $width_unit = $request->width_unit[$i];
                $thick_unit = $request->thick_unit[$i];

                $data = [
                    'product_id' => $productId,
                    'item_id' => $item_id,
                    'quantity' => $quantity,
                    'length' => $length,
                    'width' => $width,
                    'thick' => $thick,
                    'length_unit' => $length_unit,
                    'width_unit' => $width_unit,
                    'thick_unit' => $thick_unit,
                    'created_at' => $nowTime,
                    'updated_at' => $nowTime
                ];
                array_push($bomItems, $data);
            }

            // Delete All Old Mappings
            ProductBomMapping::where('product_id', $productId)->delete();

            // Save New Mappings
            ProductBomMapping::insert($bomItems);
        }

        Helper::logSystemActivity('Products', 'Update BOM Mapping for product model: ' . $product->model_name);

        // Back to index with success
        return redirect()->route('products.bom.mapping', $id)->with('custom_success', 'Product BOM Mapping has been Updated successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function storeBomMappingUploadFile($id, Request $request)
    {
        // Validations
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv',
        ]);

        // Get Product whose BOM Mapping will be updated
        $product = Product::findOrFail($id);

        // Get file path from the temp storage
        $filePath = $request->file('file');

        // Process and import BOM Items
        Excel::import(new BomImport($request), $filePath);

        Helper::logSystemActivity('Products', 'Update BOM Mapping by File Upload for product model: ' . $product->model_name);

        // success
        return response()->json([
            'success' => true,
            'message' => 'Product BOM Mapping has been Updated successfully',
        ]);
    }
}
