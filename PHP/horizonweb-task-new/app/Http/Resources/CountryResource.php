<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * CountryResource
 *
 * Transforms a Country model instance into a clean JSON structure
 * for API responses. It simplifies relationships so the client
 * only receives essential fields.
 */
class CountryResource extends JsonResource
{
    /**
     * Convert the resource into an array representation.
     *
     * @param  \Illuminate\Http\Request  $request  The current HTTP request.
     * @return array<string,mixed>  Key/value pairs for the JSON response.
     */
    public function toArray($request): array
    {
        return [
            // Country name (e.g., "Belgium")
            'name' => $this->name,

            // Two-letter ISO country code (e.g., "be")
            'code' => $this->code,

            // List of language codes, only loaded if the relation is eager loaded
            'languages' => $this->whenLoaded(
                'languages',
                fn () => $this->languages->pluck('code')->values()
            ),

            // List of category names, only loaded if the relation is eager loaded
            'categories' => $this->whenLoaded(
                'categories',
                fn () => $this->categories->pluck('name')->values()
            ),
        ];
    }
}
