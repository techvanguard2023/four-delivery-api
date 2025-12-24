<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlanController extends Controller
{
    /**
     * Display a listing of plans with their features.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $plans = Plan::with('features')
            ->where('status', 'active')
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'data' => $plans
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified plan with its features.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $plan = Plan::with('features')->find($id);

        if (!$plan) {
            return response()->json([
                'message' => 'Plano não encontrado.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $plan
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created plan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|string|unique:plans,slug',
            'price' => 'required|numeric|min:0',
            'stripe_price_id' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'status' => 'nullable|string|in:active,inactive',
            'is_free' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
        ]);

        $plan = Plan::create($validated);

        return response()->json([
            'message' => 'Plano criado com sucesso.',
            'data' => $plan
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified plan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'message' => 'Plano não encontrado.'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'sometimes|string|unique:plans,slug,' . $id,
            'price' => 'sometimes|numeric|min:0',
            'stripe_price_id' => 'nullable|string',
            'duration' => 'sometimes|integer|min:1',
            'status' => 'nullable|string|in:active,inactive',
            'is_free' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
        ]);

        $plan->update($validated);

        return response()->json([
            'message' => 'Plano atualizado com sucesso.',
            'data' => $plan
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified plan from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'message' => 'Plano não encontrado.'
            ], Response::HTTP_NOT_FOUND);
        }

        $plan->delete();

        return response()->json([
            'message' => 'Plano excluído com sucesso.'
        ], Response::HTTP_OK);
    }

    public function showPlans()
    {
        $plans = Plan::with('features')
            ->where('status', 'active')
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'data' => $plans
        ], Response::HTTP_OK);
    }
}
