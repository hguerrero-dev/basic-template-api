<?php

namespace App\Modules\Users\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),

            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(fn($r) => [
                    'id' => $r->id,
                    'name' => $r->name
                ]);
            }),
        ];
    }
}
