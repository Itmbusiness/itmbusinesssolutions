<?php

namespace Database\Factories;

use App\Enums\ConsultantCustomerStage;
use App\Models\ConsultantCustomer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConsultantCustomer>
 */
class ConsultantCustomerFactory extends Factory
{
    protected $model = ConsultantCustomer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'consultant_id' => User::factory()->consultant(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'stage' => ConsultantCustomerStage::New,
        ];
    }
}
