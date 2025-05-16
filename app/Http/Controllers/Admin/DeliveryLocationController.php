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
}
