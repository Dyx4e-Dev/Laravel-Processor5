<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Glossary;

class GlossaryController extends Controller
{
    public function index()
    {
        $glossaries = Glossary::orderBy('id')->get();

        return view('backend.glossary', compact('glossaries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'explanation' => 'required|string',
        ]);

        Glossary::create($validated);

        return back()->with('success', 'Glosarium berhasil ditambah!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'explanation' => 'required|string',
        ]);

        $glossary = Glossary::findOrFail($id);
        $glossary->update($validated);

        return back()->with('success', 'Glosarium berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Glossary::findOrFail($id)->delete();

        return back()->with('success', 'Glosarium berhasil dihapus!');
    }
}
