<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderType; // Add this line to import the OrderType class

class OrderTypeController extends Controller
{
    public function index()
    {
        return OrderType::all();
    }
}
