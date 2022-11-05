<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_no' => $this->order_no,
            'cart_total' => $this->cart_total,
            'customer_name' => $this->customer_name,
            'customer_avatar' => $this->customer_avatar,
            'created_at' => $this->created_at,
            'seen' => $this->user_id ? true : false
        ];
    }
}
