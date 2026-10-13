<?php

namespace Tests\Feature;

use App\Models\ConsultantCustomer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultantCrmTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_client_cannot_reach_the_consultant_crm(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('consultant.customers.index'))->assertForbidden();
    }

    public function test_a_consultant_can_add_a_customer(): void
    {
        $consultant = User::factory()->consultant()->create();
        $this->actingAs($consultant);

        $response = $this->post(route('consultant.customers.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0771234567',
            'stage' => 'new',
        ]);

        $customer = ConsultantCustomer::first();

        $response->assertRedirect(route('consultant.customers.show', $customer));
        $this->assertSame($consultant->id, $customer->consultant_id);
        $this->assertSame('Jane Doe', $customer->name);
    }

    public function test_a_consultant_sees_only_their_own_customers(): void
    {
        $consultant = User::factory()->consultant()->create();
        $other = User::factory()->consultant()->create();

        $mine = ConsultantCustomer::factory()->for($consultant, 'consultant')->create(['name' => 'Mine']);
        ConsultantCustomer::factory()->for($other, 'consultant')->create(['name' => 'Not mine']);

        $this->withoutVite()->actingAs($consultant);

        $this->get(route('consultant.customers.index'))
            ->assertOk()
            ->assertSee('Mine')
            ->assertDontSee('Not mine');
    }

    public function test_a_consultant_cannot_view_another_consultants_customer(): void
    {
        $owner = User::factory()->consultant()->create();
        $intruder = User::factory()->consultant()->create();

        $customer = ConsultantCustomer::factory()->for($owner, 'consultant')->create();

        $this->actingAs($intruder);

        $this->get(route('consultant.customers.show', $customer))->assertForbidden();
    }

    public function test_a_consultant_can_add_a_note_to_their_customer(): void
    {
        $consultant = User::factory()->consultant()->create();
        $customer = ConsultantCustomer::factory()->for($consultant, 'consultant')->create();

        $this->actingAs($consultant);

        $this->post(route('consultant.customers.notes.store', $customer), [
            'body' => 'Called them about the registration.',
        ])->assertRedirect(route('consultant.customers.show', $customer));

        $this->assertDatabaseHas('consultant_customer_notes', [
            'consultant_customer_id' => $customer->id,
            'body' => 'Called them about the registration.',
        ]);
    }

    public function test_a_consultant_can_add_and_toggle_a_task(): void
    {
        $consultant = User::factory()->consultant()->create();
        $customer = ConsultantCustomer::factory()->for($consultant, 'consultant')->create();

        $this->actingAs($consultant);

        $this->post(route('consultant.customers.tasks.store', $customer), [
            'title' => 'Follow up on Friday',
        ])->assertRedirect(route('consultant.customers.show', $customer));

        $task = $customer->tasks()->first();
        $this->assertNotNull($task);
        $this->assertNull($task->completed_at);

        $this->patch(route('consultant.tasks.toggle', $task))->assertRedirect();

        $this->assertNotNull($task->refresh()->completed_at);
    }
}
