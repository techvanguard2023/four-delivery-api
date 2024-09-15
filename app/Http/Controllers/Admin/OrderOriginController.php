<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderOrigin;

use Illuminate\Http\Request;

class OrderOriginController extends Controller
{
    public function index()
    {
        return OrderOrigin::all();
    }
}
