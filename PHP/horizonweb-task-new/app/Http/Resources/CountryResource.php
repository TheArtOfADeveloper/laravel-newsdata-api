<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name'       => $this->name,
            'code'       => $this->code,
            'languages'  => $this->whenLoaded('languages', fn () => $this->languages->pluck('code')->values()),
            'categories' => $this->whenLoaded('categories', fn () => $this->categories->pluck('name')->values()),
        ];
    }
}
