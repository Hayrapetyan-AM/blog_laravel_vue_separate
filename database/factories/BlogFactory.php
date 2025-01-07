<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog;
use App\Models\User; // Make sure to include the User model
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // If you are using DB for other needs like `now()`

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $now = now(); // Store the current timestamp in a variable

        return [
            'title' => $this->faker->sentence(), // Generates a random sentence for title
            'context' => $this->faker->paragraphs(3, true), // Generates 3 paragraphs for content
            'image' => $this->faker->imageUrl(640, 480, 'technics'), // Generates a random image URL
            'user_id' => 1, // Sets the user_id to 1 by default
            'created_at' => $now, // Use the stored variable
            'updated_at' => $now, // Use the stored variable
        ];
    }
}
