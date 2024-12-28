<?php
namespace App\Http\Controllers;

use App\Models\Drawing;
use Illuminate\Http\Request;

class DrawingController extends Controller
{
    public function index()
    {
        // Fetch all drawings
        return response()->json(Drawing::all());
    }

    public function store(Request $request)
    {
        // Validate and store the drawing
        $validated = $request->validate([
            'name' => 'nullable|string',
            'elements' => 'required|json',
            'appState' => 'required|json',
        ]);

        $drawing = Drawing::create($validated);

        return response()->json(['message' => 'Drawing saved!', 'data' => $drawing], 201);
    }

    public function show($id)
    {
        // Fetch a specific drawing
        $drawing = Drawing::findOrFail($id);
        return response()->json(['data' => $drawing]);
    }

    public function update(Request $request, $id)
    {
        // Update a specific drawing
        $validated = $request->validate([
            'name' => 'nullable|string',
            'elements' => 'required|json',
            'appState' => 'required|json',
        ]);

        $drawing = Drawing::findOrFail($id);
        $drawing->update($validated);

        return response()->json(['message' => 'Drawing updated!', 'data' => $drawing]);
    }

    public function destroy($id)
    {
        // Delete a specific drawing
        $drawing = Drawing::findOrFail($id);
        $drawing->delete();

        return response()->json(['message' => 'Drawing deleted!']);
    }
    public function defaultDrawing()
    {
        // Fetch the latest drawing or create a blank one if none exists
        $drawing = Drawing::latest()->first() ?? [
            'id' => null,
            'name' => 'Default Drawing',
            'elements' => '[]',
            'appState' => '{}',
        ];

        return response()->json(['data' => $drawing]);
    }
}
