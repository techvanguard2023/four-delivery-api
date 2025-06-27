<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryLocation;
use Illuminate\Http\Request;

class DeliveryLocationController extends Controller
{
    public function index(Request $request)
    { 
        $user = $request->user();

        return DeliveryLocation::where('company_id', $user->company_id)->get();
    }

    public function show(Request $request, $locationId)
    {
        $user = $request->user();

        $location = DeliveryLocation::where('id', $locationId)
                    ->where('company_id', $user->company_id)
                    ->firstOrFail();

        return response()->json($location);
    }


    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'tax' => 'required|numeric',
            'is_active' => 'boolean',
        ]);


        $location = DeliveryLocation::create([
            'company_id' => $user->company_id,
            'name' => $request->name,
            'tax' => $request->tax,
            'is_active' => $request->active ?? 'false',
        ]);
        return $location;
    }

    public function update(Request $request, $locationId)
    {
        $user = $request->user();
        $location = DeliveryLocation::where('id', $locationId)
                    ->where('company_id', $user->company_id)
                    ->firstOrFail();

        $request->validate([
            'name' =>'sometimes|string|max:255',
            'tax' =>'sometimes|numeric',
            'is_active' => 'sometimes|boolean',
        ]);

        $location->update($request->all());
        return $location;
    }

    public function destroy(Request $request, $locationId)
    {
        $user = $request->user();
        $location = DeliveryLocation::where('id', $locationId)
                    ->where('company_id', $user->company_id)
                    ->firstOrFail();
        $location->delete();
        return response()->json(['message' => 'Location deleted successfully'], 200);
    }
}
