<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'path' => $this->faker->imageUrl,
            'imageable_type' => 'App\Models\Author',
            'imageable_id' => Author::inRandomOrder()->value('id')
        ];
    }
}
