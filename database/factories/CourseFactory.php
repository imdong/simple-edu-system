<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
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
            'date' =>$this->faker->dateTimeBetween('-2 years', '+1 years')->format('Y-m-01'),
            'cost' => $this->faker->randomFloat(2, 100, 1000),
            'student_id' => $this->faker->numberBetween(1, 150)
        ];
    }
}
