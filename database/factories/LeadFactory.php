<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => Lead::generateReference(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('+263 7# ### ####'),
            'organisation_name' => fake()->company(),
            'organisation_status' => fake()->randomElement(['not-registered', 'registered', 'unsure']),
            'goal' => fake()->randomElement([
                'start-a-business', 'tax-compliance', 'tenders',
                'employer-compliance', 'organisations-trusts', 'not-sure',
            ]),
            'message' => fake()->optional()->sentence(),
            'preferred_channel' => fake()->randomElement(['whatsapp', 'email', 'phone']),
            'urgency' => fake()->randomElement(['exploring', 'this-month', 'urgent']),
            'source' => 'website',
            'status' => LeadStatus::New,
            'consent_at' => now(),
        ];
    }

    public function status(LeadStatus $status): static
    {
        return $this->state(fn (array $attributes) => ['status' => $status]);
    }
}
