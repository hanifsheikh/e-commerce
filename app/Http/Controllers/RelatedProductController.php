<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RelatedProductController extends Controller
{
    //
    protected $fillable = [
        'main_product_id',
        'related_product_id'
    ];
}
