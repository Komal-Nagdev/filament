<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Conference;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendee>
 */
class AttendeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'ticket_cost' => 50000,
            'is_paid' => true,
            'conference_id' => $this->faker->randomElement([1,2,3,]),
        ];
    }

    // public function forConference(Conference $conference): self
    // {
    //     return $this->state([
    //         'conference_id' => $conference->id,
    //     ]);
    // }
}
