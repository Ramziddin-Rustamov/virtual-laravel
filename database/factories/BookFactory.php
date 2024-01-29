<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $imageUrls = [
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTqT8vDOZfOcnbe_55u3xi_SsVzMiRolKSvmw&usqp=CAU',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRbfVLbzDyicdAFx1g1ILh5S0K47XzI5XEV7A&usqp=CAU',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1pJWVpxvvbfjz9ZLLmJu5ReyZ3adeSCTrNCJsrhVhFhboD9oD6Z9UPXVA_PnGo8fZwBI&usqp=CAU',
        ];

        return [
            'title' => $this->faker->name(),
            'category_id' => Category::all()->random()->id,
            'slug' => $this->faker->slug(),
            'author' => $this->faker->name(),
            'description' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(0, 5),
            'image' => $this->faker->randomElement($imageUrls),
        ];
    }
}