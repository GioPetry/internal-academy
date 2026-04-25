<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_for_workshop(): void
    {
        $user = User::factory()->create();
        $workshop = Workshop::create([
            'title' => 'Test Workshop',
            'instructor' => 'John Doe',
            'scheduled_at' => now()->addDay(),
            'duration_minutes' => 60,
            'max_participants' => 20,
        ]);

        $response = $this->actingAs($user)->post("/workshops/{$workshop->id}/register");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'status' => 'registered',
        ]);
    }

    public function test_user_is_waitlisted_when_workshop_is_full(): void
    {
        $user = User::factory()->create();
        $workshop = Workshop::create([
            'title' => 'Test Workshop',
            'instructor' => 'John Doe',
            'scheduled_at' => now()->addDay(),
            'duration_minutes' => 60,
            'max_participants' => 1,
        ]);

        $otherUser = User::factory()->create();
        Registration::create([
            'user_id' => $otherUser->id,
            'workshop_id' => $workshop->id,
            'status' => 'registered',
        ]);

        $response = $this->actingAs($user)->post("/workshops/{$workshop->id}/register");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'status' => 'waitlisted',
        ]);
    }

    public function test_user_cannot_register_for_overlapping_workshop(): void
    {
        $user = User::factory()->create();

        $workshop1 = Workshop::create([
            'title' => 'Workshop 1',
            'instructor' => 'John Doe',
            'scheduled_at' => now()->addDay()->setHour(10),
            'duration_minutes' => 60,
            'max_participants' => 20,
        ]);

        $this->actingAs($user)->post("/workshops/{$workshop1->id}/register");

        $workshop2 = Workshop::create([
            'title' => 'Workshop 2',
            'instructor' => 'Jane Doe',
            'scheduled_at' => now()->addDay()->setHour(10)->addMinutes(30),
            'duration_minutes' => 60,
            'max_participants' => 20,
        ]);

        $response = $this->actingAs($user)->post("/workshops/{$workshop2->id}/register");

        $response->assertSessionHas('error');
    }
}