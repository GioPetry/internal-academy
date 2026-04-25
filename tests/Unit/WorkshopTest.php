<?php

namespace Tests\Unit;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkshopTest extends TestCase
{
    use RefreshDatabase;

    public function test_workshop_can_be_created(): void
    {
        $workshop = Workshop::create([
            'title' => 'Test Workshop',
            'description' => 'Test Description',
            'instructor' => 'John Doe',
            'scheduled_at' => now()->addDay(),
            'duration_minutes' => 60,
            'max_participants' => 20,
        ]);

        $this->assertDatabaseHas('workshops', [
            'title' => 'Test Workshop',
        ]);
    }

    public function test_workshop_available_slots_calculation(): void
    {
        $workshop = Workshop::create([
            'title' => 'Test Workshop',
            'instructor' => 'John Doe',
            'scheduled_at' => now()->addDay(),
            'duration_minutes' => 60,
            'max_participants' => 2,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Registration::create([
            'user_id' => $user1->id,
            'workshop_id' => $workshop->id,
            'status' => 'registered',
        ]);

        Registration::create([
            'user_id' => $user2->id,
            'workshop_id' => $workshop->id,
            'status' => 'registered',
        ]);

        $this->assertEquals(0, $workshop->fresh()->availableSlots());
    }

    public function test_workshop_is_full(): void
    {
        $workshop = Workshop::create([
            'title' => 'Test Workshop',
            'instructor' => 'John Doe',
            'scheduled_at' => now()->addDay(),
            'duration_minutes' => 60,
            'max_participants' => 1,
        ]);

        $user = User::factory()->create();

        Registration::create([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'status' => 'registered',
        ]);

        $this->assertTrue($workshop->fresh()->isFull());
    }
}