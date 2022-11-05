<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryCache extends Model
{
    protected $casts = [
        'childrens' => 'array'
    ];
    protected $fillable = [
        'category_name',
        'category_image',
        'category_url',
        'category_thumbnail',
        'id',
        'childrens',
    ];
}
