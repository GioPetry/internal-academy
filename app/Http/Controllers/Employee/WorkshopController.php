<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WorkshopController extends Controller
{
    public function index()
    {
        $workshops = Workshop::orderBy('scheduled_at', 'asc')
            ->where('scheduled_at', '>', now())
            ->paginate(10);
        return Inertia::render('Employee/Workshops/Index', ['workshops' => $workshops]);
    }

    public function show(Workshop $workshop)
    {
        return Inertia::render('Employee/Workshops/Show', [
            'workshop' => $workshop->load('registrations.user'),
        ]);
    }
}