<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $genre = [
            'documentary' => 1,
            'drama' => 2,
            'comedy' => 3,
            'thriller' => 4,
            'fantastic' => 5
        ];
        $name = array_rand($genre);
        unset($genre[$name]);
        return [
            'name' => array_rand($genre),
            'image' => $this->faker->imageUrl
        ];
    }
}
