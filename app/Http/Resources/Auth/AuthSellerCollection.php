<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthSellerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permissionsArray = [
            'dashboard-view',
            'product-create',
            'product-update',
            'product-delete',
            'product-view',
            'order-view',
            'order-update',
            'payment-view',
            'material-view',
            'tutorial-view',
            'sale-view',
            'wishlist-view',
            'shop-view'
        ];
        return [
            'user' => [
                'name' => $this->collection['user']->name,
                'company_name' => $this->collection['user']->company_name,
                'avatar' => $this->collection['user']->avatar,
                'email' => $this->collection['user']->email,
                'address' => $this->collection['user']->address,
                'logo' => $this->collection['user']->logo,
                'banner' => $this->collection['user']->banner,
                'theme' => $this->collection['user']->theme,
                'permissions' => $permissionsArray,
            ],
            'approved' => $this->collection['user']->documents_approved_at ? true : false,
            'documents_submitted' => $this->collection['user']->documents_submitted_at ? true : false,
            'documents_declined' => $this->collection['user']->documents_declined_at ? true : false,
            'documents_declined_at' => $this->collection['user']->documents_declined_at,
            'token' => $this->collection['token']
        ];
    }
}
