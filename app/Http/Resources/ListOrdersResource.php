<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListOrdersResource extends JsonResource
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
            'customer' => $this->customer->name ?? '',
            'customer_phone' => $this->customer->phone ?? '',
            'customer_id' => $this->customer->id ?? '',
            'order_type' => $this->orderType->name ?? '',
            'total_price' => $this->total_price,
            'status' => $this->status->name ?? 'Pending',
            'status_id' => $this->status_id,
            'payment_status' => $this->payment_status ?? 'Unpaid',
            //'payment_method' => $this->payment_method ?? '',
            'total_items' => $this->orderItems->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'delivery_person' => $this->deliveryPerson->name ?? 'Not assigned',
            //'location' => $this->location ?? null,
            'position' => $this->position ?? null,
            'status_id' => $this->status_id,
        ];
    }
}
