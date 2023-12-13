<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TodoItem>
 */
class TodoItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->word(),
            'description'  => $this->faker->text(),
            'status'       => $this->faker->randomElement([
                \App\Models\TodoItem::STATUS_PENDENT,
                \App\Models\TodoItem::STATUS_DONE,
            ]),
            'completed_at' => $this->faker->randomElement([
                null,
                $this->faker->dateTime(),
            ]),
        ];
    }
}
