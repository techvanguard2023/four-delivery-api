<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'original_price' => $this->original_price,
            'price' => $this->price,
            'category' => $this->category->name,
            'category_id' => $this->category->id,
            'stock' => $this->stock->quantity,
            'available' => $this->available,
            'show_in_menu' => $this->show_in_menu,
            'highlighted' => $this->highlighted,
            'created_at' => $this->created_at,
        ];
    }
}
