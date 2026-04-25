<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Models\Workshop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWorkshopReminders extends Command
{
    protected $signature = 'workshops:send-reminders {--days=1 : Giorni prima del workshop per inviare promemoria}';
    protected $description = 'Invia promemoria agli iscritti dei workshop imminenti';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $targetDate = now()->addDays($days);

        $workshops = Workshop::whereDate('scheduled_at', $targetDate->toDateString())
            ->with('registrations.user')
            ->get();

        $count = 0;

        foreach ($workshops as $workshop) {
            foreach ($workshop->registrations as $registration) {
                if ($registration->status !== 'registered') {
                    continue;
                }

                $user = $registration->user;
                $this->info("Inviando promemoria a {$user->email} per {$workshop->title}");
                $count++;
            }
        }

        $this->info("Completato. {$count} promemoria inviati.");
        return Command::SUCCESS;
    }
}