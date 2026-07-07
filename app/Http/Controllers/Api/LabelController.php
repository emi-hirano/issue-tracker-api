<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Label;

class LabelController extends Controller
{
    public function index()
    {
        return Label::all();
    }

    public function show($id)
    {
        return Label::findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'nullable|string',
        ]);

        $label = Label::create($validated);
        return $label;
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'nullable|string',
        ]);

        $label = Label::findOrFail($id);
        $label->update($validated);
        return $label;
    }

    public function destroy($id)
    {
        $label = Label::findOrFail($id);
        $label->delete();
        return response()->json(['message' => '削除しました']);
    }
}
