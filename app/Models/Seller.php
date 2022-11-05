<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Seller extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'active',
        'is_product_banned',
        'is_feature_banned',
        'logo',
        'banner',
        'commission_rate',
        'avatar',
        'company_name',
        'shop_slug',
        'email',
        'owner_address',
        'company_address',
        'url',
        'contact_no',
        'alternative_contact_no',
        'password',
        'theme',
        'selling_products',
        'email_verified_at',
        'documents_submitted_at',
        'documents_approved_at',
        'documents_declined_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'laravel_through_key'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
