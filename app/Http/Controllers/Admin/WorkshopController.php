<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkshopController extends Controller
{
    public function index()
    {
        $workshops = Workshop::orderBy('scheduled_at', 'desc')->paginate(10);
        return Inertia::render('Admin/Workshops/Index', ['workshops' => $workshops]);
    }

    public function create()
    {
        return Inertia::render('Admin/Workshops/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor' => 'required|string|max:255',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:15',
            'max_participants' => 'required|integer|min:1',
        ]);

        Workshop::create($validated);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop creato con successo');
    }

    public function edit(Workshop $workshop)
    {
        return Inertia::render('Admin/Workshops/Edit', ['workshop' => $workshop]);
    }

    public function update(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructor' => 'required|string|max:255',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'max_participants' => 'required|integer|min:1',
        ]);

        $workshop->update($validated);

        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop aggiornato con successo');
    }

    public function destroy(Workshop $workshop)
    {
        $workshop->delete();
        return redirect()->route('admin.workshops.index')
            ->with('success', 'Workshop eliminato con successo');
    }
}