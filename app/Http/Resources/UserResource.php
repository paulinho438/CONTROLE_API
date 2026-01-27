<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'nome_completo' => $this->nome_completo,
            'email' => $this->email,
            'status' => $this->status,
            'groups' => $this->whenLoaded('groups', function () {
                return $this->groups->map(fn($group) => [
                    'id' => $group->id,
                    'name' => $group->name
                ])->values();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
