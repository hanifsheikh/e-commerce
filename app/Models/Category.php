<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'category_name',
        'category_url',
        'category_image',
        'category_thumbnail',
        'parent_id',
    ];

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
    public function haveChild()
    {
        return $this->hasOne(Category::class, 'parent_id', 'id');
    }
}
