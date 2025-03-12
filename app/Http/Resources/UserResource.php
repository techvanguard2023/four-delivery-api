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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'company' => $this->company->name,
            'fantasy_name' => $this->company->fantasy_name,
            'company_id' => $this->company->id,
            'user_role' => $this->roles[0]->name,
            'roles' => $this->roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return [
                            'id' => $permission->id,
                            'name' => $permission->name,
                            'tag' => $permission->tag,
                        ];
                    }),
                ];
            }),
            'plans' => $this->company?->companyPlans?->map(function ($companyPlan) {
                    return [
                        'id' => $companyPlan->plan?->id,
                        'name' => $companyPlan->plan?->name,
                        'features' => $companyPlan->plan?->features?->map(function ($feature) {
                                return [
                                    'id' => $feature->id,
                                    'name' => $feature->name,
                                    'description' => $feature->description,
                                ];
                            }) ?? [], // Se features for null, retorna um array vazio
                    ];
                }) ?? [], // Se companyPlans for null, retorna um array vazio
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
