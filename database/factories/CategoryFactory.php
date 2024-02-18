<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::where("type",1)->orWhere('type',3)->pluck('id')->toArray();
        return [
            'name' => fake()->text('15'),
            'description'=> fake()->slug(),
            'created_by' => fake()->randomElement($users),
        ];
    }
}
