<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Image;
use App\Models\State;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(30)->create();
        Author::factory(10)->create();
        Category::factory(5)->create();
        $this->call(BooksTableSeeder::class);
        Image::factory(20)->create();
        Tag::factory(10)->create();
        $articles = Article::factory(30)->create();
        $articles->each(function ($article)  {
            State::factory(1)->create([
                'article_id' => $article->id
            ]);
        });
        Comment::factory(50)->create();
    }
}
