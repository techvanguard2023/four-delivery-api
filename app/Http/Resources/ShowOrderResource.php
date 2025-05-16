<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowOrderResource extends JsonResource
{
    /**
     * Transform the resource into um array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => [
                'name' => $this->customer->name ?? null,
                'email' => $this->customer->email ?? null,
                'phone' => $this->customer->phone ?? null,
                'address' => $this->customer->address ?? null,
            ],
            // Apenas retorna delivery_addresses se o customer tiver endereços de entrega
            'delivery_addresses' => $this->customer && $this->customer->deliveryAddresses->isNotEmpty()
                ? $this->customer->deliveryAddresses->map(function ($address) {
                    return [
                        'address' => $address->address,
                        'number' => $address->number,
                        'complement' => $address->complement,
                        'neighborhood' => $address->neighborhood,
                        'reference_point' => $address->reference_point,
                        'city' => $address->city,
                        'state' => $address->state,
                        'zip_code' => $address->zip_code,
                    ];
                })
                : null,
            'order_items' => $this->orderItems->map(function ($orderItem) {
                return [
                    'id' => $orderItem->id,
                    'item_name' => $orderItem->item->name,
                    'item_description' => $orderItem->item->description,
                    'item_image_url' => $orderItem->item->image_url,
                    'quantity' => $orderItem->quantity,
                    'observation' => $orderItem->observation,
                    'price' => $orderItem->price,
                    'total' => $orderItem->quantity * $orderItem->price,
                ];
            }),
            'delivery_location_id' => $this->delivery_location_id,
            'delivery_location' => $this->deliveryLocation->name?? null,
            'delivery_tax' => $this->deliveryLocation->tax?? null,
            'total_price' => $this->total_price,
            'status' => $this->status->name ?? null,
            'payment_status' => $this->payment_status ?? null,
            'created_at' => $this->created_at->toDateTimeString(),
            'delivery_person' => $this->deliveryPerson->name ?? null,
            'location' => $this->location ?? null,
            'order_origin' => $this->orderOrigin->name ?? null,
        
        ];
    }
}
