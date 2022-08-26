<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function productPictures()
    {
        return $this->hasMany(ProductPicture::class);
    }

    /**
     * Get the Parent product associated with the product.
     */
    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the subcategory associated with the product.
     */
    public function subCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'subcategory_id');
    }
    
    /**
     * Get the bomcategory associated with the product.
     */
    public function bomCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'bomcategory_id');
    }

    /**
     * Get the packing associated with the product.
     */
    public function packing()
    {
        return $this->hasOne(ProductPacking::class, 'product_id', 'id');
    }
}
