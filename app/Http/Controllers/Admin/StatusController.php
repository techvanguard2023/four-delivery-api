<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        return Status::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:statuses,name'
        ]);

        $status = Status::create($validatedData);

        return response()->json($status, 201);
    }

    public function show(Status $status)
    {
        return $status;
    }

    public function update(Request $request, Status $status)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255|unique:statuses,name,' . $status->id
        ]);

        $status->update($validatedData);

        return response()->json($status, 200);
    }

    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json(null, 204);
    }
}
