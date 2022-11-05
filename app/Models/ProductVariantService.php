<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantService extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_variant_id",
        "delivery_charge",
        "payment_first",
        "free_delivery_upto",
        "delivery_area",
        "delivery_charge_outside",
        "payment_first_amount_in_percentage",
        "payment_first_amount_in_taka",
        "payment_first_delivery_charge",
        "replacement_in_days",
        "gurantee_in_months",
        "warranty_in_months",
    ];
}
