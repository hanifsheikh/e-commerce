<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        "product_id",
        "seller_id",
        "offer_id",
        "product_title",
        "product_variant_title",
        "sku",
        "shape",
        "item_diameter",
        "weight",
        "authenticity",
        "color",
        "color_code",
        'texture',
        "model_no",
        "country_of_origin",
        "size",
        "material",
        "stock_quantity",
        "regular_price",
        "offer_price",
        "delivery_time",
        "discount_in_percentage",
        "cash_on_delivery",
        "total_sales",
        "product_variant_url"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function main_image()
    {
        return $this->hasOne(ProductImage::class, 'product_variant_id', 'id')->where('position', 1);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_variant_id', 'id');
    }
    public function services()
    {
        return $this->hasOne(ProductVariantService::class, 'product_variant_id', 'id');
    }
    public function meta()
    {
        return $this->hasOne(ProductVariantMeta::class, 'product_variant_id', 'id');
    }
    public function seller()
    {
        return $this->hasOneThrough(Seller::class, Product::class, 'id', 'id', 'product_id', 'seller_id');
    }
    public function variants()
    {
        return $this->hasManyThrough(ProductVariant::class, Product::class, 'id', 'product_id', 'product_id', 'id');
    }
    public function brand()
    {
        return $this->hasOneThrough(Brand::class, Product::class, 'id', 'id', 'product_id', 'brand_id');
    }
}
