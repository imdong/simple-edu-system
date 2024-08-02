<?php

namespace Database\Factories;

use Faker\Provider\DateTime;
use Faker\Provider\Person;
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
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new DateTime($this->faker));

        return [
            'name'       => $this->faker->name(),
            'date'       => $this->faker->dateTimeBetween('-2 years', '+1 years')->format('Y-m-01'),
            'cost'       => $this->faker->randomFloat(2, 100, 1000),
            'teacher_id' => $this->faker->numberBetween(1, 50),
            'student_id' => $this->faker->numberBetween(1, 150),
        ];
    }
}
