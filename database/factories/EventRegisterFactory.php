<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'event_id' => $this->faker->numberBetween(1, 2),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber,
            'affiliation' => $this->faker->company,
            'ticket_type' => $this->faker->randomElement(['free', 'paid']),
            'notes' => $this->faker->sentence,
        ];
    }
}
