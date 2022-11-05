<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_variant_id",
        "about_the_item",
        "product_description",
        "keywords",
        "product_variant_embed_video_url",
        "product_components",
        "product_components_ratio_per_gram"
    ];
}
