<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $last_seen = $this->last_seen ? Carbon::parse($this->last_seen)->diffForHumans() : null;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'contact' => $this->contact,
            'address' => $this->address,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'last_seen' => $last_seen,
            'email_verified' => $this->email_verified_at ? 'yes' : 'no'
        ];
    }
}
