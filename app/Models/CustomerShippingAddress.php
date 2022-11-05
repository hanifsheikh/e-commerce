<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        "customer_id",
        "receiver_name",
        "receiver_contact_no",
        "receiver_address",
        "area",
        "district",
        "label",
    ];
}
