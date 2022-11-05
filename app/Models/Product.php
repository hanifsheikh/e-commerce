<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_title",
        "category_id",
        "category_parent_id",
        "category_second_level_id",
        'active',
        "unit",
        "offer_id",
        "brand_id",
        "seller_id",
        "ratings",
        "total_sales"
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function category_second_level()
    {
        return $this->belongsTo(Category::class, 'category_second_level_id', 'id');
    }
    public function category_first_level()
    {
        return $this->belongsTo(Category::class, 'category_parent_id', 'id');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }
    public function customer_reviews()
    {
        return $this->hasMany(CustomerReview::class, 'product_id', 'id');
    }
    public function image()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id');
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
