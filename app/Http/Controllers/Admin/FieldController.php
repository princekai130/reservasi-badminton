<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Field;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = Field::orderBy('id', 'desc')->get();
        // Mengirim data ke view
        return view('admin.fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo_url')) {
            $path = $request->file('photo_url')->store('fields', 'public');
            $validated['photo_url'] = $path;
        }

        Field::create($validated);

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field)
    {
        return view('admin.fields.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Field $field)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo_url')) {
            // hapus file lama jika ada
            if ($field->photo_url) {
                Storage::disk('public')->delete($field->photo_url);
            }
            $validated['photo_url'] = $request->file('photo_url')->store('fields', 'public');
        }

        $field->update($validated);

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field)
    {
        if ($field->photo_url) {
            Storage::disk('public')->delete($field->photo_url);
        }
        $field->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
