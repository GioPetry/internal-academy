<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistrationController extends Controller
{
    public function store(Request $request, Workshop $workshop)
    {
        $user = $request->user();

        $existingRegistration = Registration::where('user_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'Sei già registrato a questo workshop');
        }

        $overlapping = $this->checkOverlap($user->id, $workshop);
        if ($overlapping) {
            return back()->with('error', 'Hai un altro workshop in sovrapposizione oraria: ' . $overlapping->title);
        }

        if ($workshop->isFull()) {
            $lastPosition = Registration::where('workshop_id', $workshop->id)
                ->where('status', 'waitlisted')
                ->max('position') ?? 0;

            Registration::create([
                'user_id' => $user->id,
                'workshop_id' => $workshop->id,
                'status' => 'waitlisted',
                'position' => $lastPosition + 1,
            ]);

            return back()->with('success', 'Workshop pieno. Sei in lista d attesa (posizione: ' . ($lastPosition + 1) . ')');
        }

        Registration::create([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'status' => 'registered',
        ]);

        return back()->with('success', 'Registrazione effettuata con successo');
    }

    public function destroy(Request $request, Workshop $workshop)
    {
        $registration = Registration::where('user_id', $request->user()->id)
            ->where('workshop_id', $workshop->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if (!$registration) {
            return back()->with('error', 'Non sei registrato a questo workshop');
        }

        $previousStatus = $registration->status;
        $registration->update(['status' => 'cancelled']);

        if ($previousStatus === 'registered') {
            $workshop->promoteFromWaitlist($workshop->id);
        }

        return back()->with('success', 'Registrazione cancellata');
    }

    private function checkOverlap(int $userId, Workshop $workshop): ?Workshop
    {
        $newStart = $workshop->scheduled_at;
        $newEnd = $newStart->copy()->addMinutes($workshop->duration_minutes);

        return Workshop::where('id', '!=', $workshop->id)
            ->where('scheduled_at', '>', now())
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->where(function ($q) use ($newStart, $newEnd) {
                    $q->where('scheduled_at', '<', $newEnd)
                        ->whereRaw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE)', '>', $newStart);
                });
            })
            ->whereHas('registrations', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('status', 'registered');
            })
            ->first();
    }
}