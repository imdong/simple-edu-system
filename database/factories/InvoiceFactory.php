<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id'  => $this->faker->numberBetween(1, 100),
            'teacher_id' => $this->faker->numberBetween(1, 100),
            'student_id' => $this->faker->numberBetween(1, 100),
            'amount'     => $this->faker->numberBetween(0, 20000),
            'status'     => $this->faker->numberBetween(0, 5),
        ];
    }
}
