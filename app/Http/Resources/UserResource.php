<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource['name'],
            'email' => $this->resource['email'],
            'phone' => $this->resource['phone'],
            'last_name' => $this->resource['last_name'],
            'is_admin' => $this->resource['is_admin']
        ];
    }
}
