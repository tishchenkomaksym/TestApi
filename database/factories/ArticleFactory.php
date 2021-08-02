<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(6, true);
        $slug = Str::substr(Str::lower(preg_replace('/\s+/', '-', $title)), 0, -1);

        return [
            'title' => $title,
            'body' => $this->faker->realTextBetween(100, 150),
            'slug' => $slug,
            'image' => $this->faker->image,
            'author_id' => Author::inRandomOrder()->value('id'),
            'created_at' => $this->faker->dateTimeBetween('-1 years')
        ];
    }
}
