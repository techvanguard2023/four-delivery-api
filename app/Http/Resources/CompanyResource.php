<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $activeSubscription = $this->subscriptions
            ?->sortByDesc('start_date')
            ->first();

        return [
            "id" => $this->id,
            "name"  => $this->name,
            "fantasy_name"  => $this->fantasy_name,
            "slug"  => $this->slug,
            "cnpj"  => $this->cnpj,
            "cpf"   => $this->cpf,
            "email" => $this->email,
            "address"   => $this->address,
            "number"    => $this->number,
            "neighborhood"  => $this->neighborhood,
            "city"  => $this->city,
            "state" => $this->state,
            "zip_code"  => $this->zip_code,
            "country"   => $this->country,
            "phone" => $this->phone,
            "whatsapp"  => $this->whatsapp,
            "website"   => $this->website,
            "created_at"    => $this->created_at,
            "updated_at"    => $this->updated_at,
            "deleted_at"    => $this->deleted_at,
            "subscription" => $activeSubscription ? [
                "id" => $activeSubscription->id,
                "plan_id" => $activeSubscription->plan_id,
                "company_id" => $activeSubscription->company_id,
                "stripe_subscription_id" => $activeSubscription->stripe_subscription_id,
                "price" => $activeSubscription->price,
                "start_date" => $activeSubscription->start_date,
                "end_date" => $activeSubscription->end_date,
                "status" => $activeSubscription->status,
                "created_at" => $activeSubscription->created_at,
                "updated_at" => $activeSubscription->updated_at,
                "plan" => [
                    "id" => $activeSubscription->plan->id,
                    "name" => $activeSubscription->plan->name,
                    "description" => $activeSubscription->plan->description,
                    "price" => $activeSubscription->plan->price,
                    "slug" => $activeSubscription->plan->slug,
                    "duration" => $activeSubscription->plan->duration,
                    "status" => $activeSubscription->plan->status,
                    "created_at" => $activeSubscription->plan->created_at,
                    "updated_at" => $activeSubscription->plan->updated_at,
                    "features" => $activeSubscription->plan->features->map(function ($feature) {
                        return [
                            "id" => $feature->id,
                            "name" => $feature->name,
                            "description" => $feature->description,
                            "slug" => $feature->slug,
                            "created_at" => $feature->created_at,
                            "updated_at" => $feature->updated_at,
                        ];
                    }),
                ]
            ] : null,
        ];
    }
}
