<?php

namespace App\Imports;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductBomMapping;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class BomImport implements ToCollection, WithHeadingRow, WithValidation
{
    // Laravel Request Access
    protected $request;

    function __construct($request)
    {
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {
        try {
            DB::beginTransaction();

            // Delete All BOM Items
            ProductBomMapping::where('product_id', $this->request->product_id)->delete();

            $bomItemsArr = [];

            // Create new Bom Mapping Items
            foreach ($rows as $key => $row) {
                $parentProduct = null;

                // Parent Product Data
                $parentItemModelName = $this->sanitizeInput($row['parent_item_model_name']);
                $parentItemProductName = $this->sanitizeInput($row['parent_item_product_name']);
                $parentItemPricePerUnit = $this->sanitizeInput($row['parent_item_price_per_unit']);
                $parentItemMaterial = $this->sanitizeInput($row['parent_item_material']);
                $parentItemColorName = $this->sanitizeInput($row['parent_item_color_name']);
                $parentItemColorCode = $this->sanitizeInput($row['parent_item_color_code']);
                $parentItemLength = $this->sanitizeInput($row['parent_item_length']);
                $parentItemLengthUnit = $this->sanitizeInput($row['parent_item_length_unit']);
                $parentItemWidth = $this->sanitizeInput($row['parent_item_width']);
                $parentItemWidthUnit = $this->sanitizeInput($row['parent_item_width_unit']);
                $parentItemThick = $this->sanitizeInput($row['parent_item_thick']);
                $parentItemThickUnit = $this->sanitizeInput($row['parent_item_thick_unit']);
                $parentItemItemDescription = $this->sanitizeInput($row['parent_item_item_description']);
                $parentItemCategoryName = $this->sanitizeInput($row['parent_item_category_name']);
                $parentItemCategoryCode = $this->sanitizeInput($row['parent_item_category_code']);
                $parentItemCategoryHasBOMItems = $this->sanitizeInput($row['parent_item_category_has_bom_items']);
                $parentItemQuantity = $this->sanitizeInput($row['parent_item_quantity']);

                // Main Product Data
                $mainItemModelName = $this->sanitizeInput($row['main_item_model_name']);
                $mainItemProductName = $this->sanitizeInput($row['main_item_product_name']);
                $mainItemPricePerUnit = $this->sanitizeInput($row['main_item_price_per_unit']);
                $mainItemMaterial = $this->sanitizeInput($row['main_item_material']);
                $mainItemColorName = $this->sanitizeInput($row['main_item_color_name']);
                $mainItemColorCode = $this->sanitizeInput($row['main_item_color_code']);
                $mainItemLength = $this->sanitizeInput($row['main_item_length']);
                $mainItemLengthUnit = $this->sanitizeInput($row['main_item_length_unit']);
                $mainItemWidth = $this->sanitizeInput($row['main_item_width']);
                $mainItemWidthUnit = $this->sanitizeInput($row['main_item_width_unit']);
                $mainItemThick = $this->sanitizeInput($row['main_item_thick']);
                $mainItemThickUnit = $this->sanitizeInput($row['main_item_thick_unit']);
                $mainItemItemDescription = $this->sanitizeInput($row['main_item_item_description']);
                $mainItemCategoryName = $this->sanitizeInput($row['main_item_category_name']);
                $mainItemCategoryCode = $this->sanitizeInput($row['main_item_category_code']);
                $mainItemCategoryHasBOMItems = $this->sanitizeInput($row['main_item_category_has_bom_items']);
                $mainItemQuantity = $this->sanitizeInput($row['main_item_quantity']);

                if(!empty($parentItemModelName) && !empty($parentItemCategoryName)) {
                    // FirstOrCreate parent Category
                    $parentProductCategory = ProductCategory::firstOrCreate(
                        [
                            'name' => $parentItemCategoryName,
                            'code' => $parentItemCategoryCode,
                        ],
                        [
                            'has_bom_items' => $parentItemCategoryHasBOMItems
                        ]
                    );

                    // updateOrCreate parent Color
                    if (!empty($parentItemColorName) && !empty($parentItemColorCode)) {
                        $parentProductColor = Color::updateOrCreate(
                            ['name' => $parentItemColorName, 'code' => $parentItemColorCode],
                            ['name' => $parentItemColorName]
                        );
                    }

                    // FirstOrCreate parent Item
                    $parentProduct = Product::firstOrCreate(
                        [
                            'model_name' => $parentItemModelName,
                            'product_name' => $parentItemProductName,
                            'category_id' => $parentProductCategory->id,
                        ],
                        [
                            'price_per_unit' => $parentItemPricePerUnit,
                            'material' => $parentItemMaterial,
                            'color_name' => $parentItemColorName,
                            'color_code' => $parentItemColorCode,
                            'length' => $parentItemLength,
                            'length_unit' => $parentItemLengthUnit,
                            'width' => $parentItemWidth,
                            'width_unit' => $parentItemWidthUnit,
                            'thick' => $parentItemThick,
                            'thick_unit' => $parentItemThickUnit,
                            'item_description' => $parentItemItemDescription,
                        ]
                    );
                }

                // FirstOrCreate main Category
                $mainProductCategory = ProductCategory::firstOrCreate(
                    [
                        'name' => $mainItemCategoryName,
                        'code' => $mainItemCategoryCode,
                    ],
                    [
                        'has_bom_items' => $mainItemCategoryHasBOMItems
                    ]
                );

                // updateOrCreate Main Color
                if(!empty($mainItemColorName) && !empty($mainItemColorCode)) {
                    $mainProductColor = Color::updateOrCreate(
                        ['name' => $mainItemColorName, 'code' => $mainItemColorCode],
                        ['name' => $mainItemColorName]
                    );
                }

                // FirstOrCreate main Item
                $mainProduct = Product::firstOrCreate(
                    [
                        'model_name' => $mainItemModelName,
                        'product_name' => $mainItemProductName,
                        'category_id' => $mainProductCategory->id,
                        'parent_id' => $parentProduct->id ?? null,
                    ],
                    [
                        'price_per_unit' => $mainItemPricePerUnit,
                        'material' => $mainItemMaterial,
                        'color_name' => $mainItemColorName,
                        'color_code' => $mainItemColorCode,
                        'length' => $mainItemLength,
                        'length_unit' => $mainItemLengthUnit,
                        'width' => $mainItemWidth,
                        'width_unit' => $mainItemWidthUnit,
                        'thick' => $mainItemThick,
                        'thick_unit' => $mainItemThickUnit,
                        'item_description' => $mainItemItemDescription,
                    ]
                );

                if($parentProduct) {
                    array_push($bomItemsArr, [
                        'product_id' => $this->request->product_id,
                        'item_id' => $parentProduct->id,
                        'quantity' => $parentItemQuantity,
                    ]);
                }
                
                array_push($bomItemsArr, [
                    'product_id' => $this->request->product_id,
                    'item_id' => $mainProduct->id,
                    'quantity' => $mainItemQuantity,
                ]);
            }

            $bomItemsArr = array_map("unserialize", array_unique(array_map("serialize", $bomItemsArr)));

            return ProductBomMapping::insert($bomItemsArr);
        } catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        } finally {
            DB::commit();
        }
    }

    public function sanitizeInput($str) {
        return trim($str);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        // Validation Rules to sanitize the Excel/CSV Data
        return [
            'parent_item_model_name'             => 'nullable|string',
            'parent_item_product_name'           => 'nullable|string',
            'parent_item_price_per_unit'         => 'nullable|numeric',
            'parent_item_material'               => 'nullable|string',
            'parent_item_color_name'             => 'nullable|string',
            'parent_item_color_code'             => 'nullable|string',
            'parent_item_length'                 => 'nullable|numeric',
            'parent_item_length_unit'            => 'nullable|string',
            'parent_item_width'                  => 'nullable|numeric',
            'parent_item_width_unit'             => 'nullable|string',
            'parent_item_height'                 => 'nullable|numeric',
            'parent_item_height_unit'            => 'nullable|string',
            'parent_item_thick'                  => 'nullable|numeric',
            'parent_item_thick_unit'             => 'nullable|string',
            'parent_item_item_description'       => 'nullable|string',
            'parent_item_category_name'          => 'nullable|string',
            'parent_item_category_code'          => 'nullable|string',
            'parent_item_category_has_bom_items' => 'nullable|boolean',
            'parent_item_quantity'               => 'nullable|numeric',
            'main_item_model_name'               => 'required|string',
            'main_item_product_name'             => 'required|string',
            'main_item_price_per_unit'           => 'nullable|numeric',
            'main_item_material'                 => 'nullable|string',
            'main_item_color_name'               => 'nullable|string',
            'main_item_color_code'               => 'nullable|string',
            'main_item_length'                   => 'nullable|numeric',
            'main_item_length_unit'              => 'nullable|string',
            'main_item_width'                    => 'nullable|numeric',
            'main_item_width_unit'               => 'nullable|string',
            'main_item_height'                   => 'nullable|numeric',
            'main_item_height_unit'              => 'nullable|string',
            'main_item_thick'                    => 'nullable|numeric',
            'main_item_thick_unit'               => 'nullable|string',
            'main_item_item_description'         => 'nullable|string',
            'main_item_category_name'            => 'required|string',
            'main_item_category_code'            => 'required|string',
            'main_item_category_has_bom_items'   => 'required|boolean',
            'main_item_quantity'                 => 'required|numeric',
        ];
    }
}
