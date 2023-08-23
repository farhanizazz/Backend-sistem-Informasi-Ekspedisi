<?php

namespace App\Http\Resources\User;

use App\Http\Traits\GlobalTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    use GlobalTrait;
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
            'username'=> $this->username,
            'm_role_id' => $this->m_role_id,
            'email' => $this->email,
            'role' => $this->role ? $this->role : $this->templateAkses(),
        ];
    }
}
