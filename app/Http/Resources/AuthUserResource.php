<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    public function toArray($request)
    {
        $permissions = [];
        foreach ($this->groups as $group) {
            foreach ($group->items as $item) {
                $permissions[] = $item->slug;
            }
        }

        return [
            'id' => $this->id,
            'nome_completo' => $this->nome_completo,
            'login' => $this->login,
            'email' => $this->email,
            'permissions' => array_values(array_unique($permissions))
        ];
    }
}

