<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'active' => $this->active,
            'is_product_banned' => $this->is_product_banned,
            'is_feature_banned' => $this->is_feature_banned,
            'company_name' => $this->company_name,
            'company_address' => $this->company_address,
            'owner_address' => $this->owner_address,
            'url' => $this->url,
            'email' => $this->email,
            'contact_no' => $this->contact_no,
            'selling_products' => $this->selling_products,
            'alternative_contact_no' => $this->alternative_contact_no,
            'email_verified' => $this->email_verified_at ? 'yes' : 'no',
            'documents_approved' => $this->documents_approved_at ? true : false,
            'documents_submitted' => $this->documents_submitted_at ? true : false,
            'nid' => $this->nid,
            'trade_license' => $this->trade_license,
            'electricity_bill' => $this->electricity_bill,
        ];
    }
}
