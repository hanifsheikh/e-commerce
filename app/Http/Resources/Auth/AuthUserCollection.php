<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthUserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permissionsArray = [];
        if ($this->collection['user']->role) {
            foreach ($this->collection['user']->role->permissions as $permission) {
                array_push($permissionsArray, $permission->permission_name);
            }
        }
        return [
            'user' => [
                'name' => $this->collection['user']->name,
                'email' => $this->collection['user']->email,
                'avatar' => $this->collection['user']->avatar,
                'address' => $this->collection['user']->address,
                'theme' => $this->collection['user']->theme,
                'role' => $this->collection['user']->role?->role_name,
                'permissions' => $permissionsArray,
            ],
            'token' => $this->collection['token']
        ];
    }
}
