<?php

namespace Database\Factories;

use App\Models\Book;
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

     protected $model = Book::class;
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'author' => $this->faker->sentence(),
            'publisher' => $this->faker->sentence(),
            'year' => $this->faker->date(),
            'image' => $this->faker->word() . '.jpg',
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
