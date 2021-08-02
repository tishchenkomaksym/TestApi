<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject' => $this->faker->sentence('3'),
            'body' => $this->faker->paragraph('3', false),
            'commentable_type' => 'App\Models\Article',
            'commentable_id' => Article::inRandomOrder()->value('id'),
            'user_id' => User::inRandomOrder()->value('id')
        ];
    }
}
