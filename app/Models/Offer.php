<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $fillable = [
        'offer_title',
        'slug',
        'offer_start',
        'offer_duration_in_days',
        'offer_end',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'offer_start' => 'datetime',
        'offer_end' => 'datetime'
    ];
}
