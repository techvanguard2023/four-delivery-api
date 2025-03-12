<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPSTORM_META\map;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name"  => $this->name,
            "fantasy_name"  => $this->fantasy_name,
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
            "subscription" => $this->plans->map(function ($plan) {
                return [
                    "id" => $plan->id,
                    "plan_id" => $plan->id,
                    "company_id" => $plan->company_id,
                    "start_date" => $plan->start_date,
                    "end_date" => $plan->end_date,
                    "status" => $plan->status,
                    "created_at"    => $plan->created_at,
                    "updated_at"    => $plan->updated_at,
                    "plan" => [
                        "id" => $plan->plan_id,
                        "name" => $plan->plan->name,
                        "description" => $plan->plan->description,
                        "price" => $plan->plan->price,
                        "slug" => $plan->plan->slug,
                        "duration" => $plan->plan->duration,
                        "status" => $plan->plan->status,
                        "created_at" => $plan->plan->created_at,
                        "updated_at" => $plan->plan->updated_at,
                        "features" => $plan->plan->features->map(function ($feature) {
                            return [
                                "id" => $feature->id,
                                "name" => $feature->name,
                                "description" => $feature->description,
                                "slug" => $feature->slug,
                                "created_at" => $feature->created_at,
                                "updated_at" => $feature->updated_at,
                            ];
                        })
                    ]
                ];
            })
        ];
    }
}
