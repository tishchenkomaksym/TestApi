<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    public function testGetArticlesWithoutSanctum()
    {
        $this->get(route('api.articles'), ['Accept' => 'application/json'])
             ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetArticles()
    {
        $this->actingAsSanctumUser();
        Author::factory()->create();
        $article = Article::factory()->create()->first()->toArray();
        $this->get(route('api.articles'), ['Accept' => 'application/json'])
             ->assertStatus(200)->assertJsonFragment($article);

    }

    public function testGetOneArticle()
    {
        $this->actingAsSanctumUser();
        Author::factory()->create();
        $article = Article::factory()->create()->first();
        $this->get(route('api.show.article', ['slug' => $article->slug]), ['Accept' => 'application/json'])
             ->assertStatus(200)->assertJsonFragment($article->toArray());

    }

    public function testCreateArticle()
    {
        Storage::fake('public');
        $this->actingAsSanctumUser();
        $this->withoutExceptionHandling();
        Author::factory()->create();
        $tag = Tag::factory()->create();
        $title = $this->faker->sentence(6, true);;
        $file = UploadedFile::fake()->image('avatar.jpg');

        $data = [
            'title' => $title,
            'body' => $this->faker->realTextBetween(100, 150),
            'image' => $file,
            'tag_id' => $tag->id,
        ];

        $this->post(route('api.create.article'), $data)
             ->assertStatus(201)
             ->assertJson(['success' => 'success']);

        $article = Article::first();

        Storage::disk('public')->assertExists($file->hashName());
        unlink(storage_path('app/public/' . $article->image));

        $this->assertEquals($title, $article->title);
    }

    public function testAddComment()
    {
        $this->actingAsSanctumUser();
        Author::factory()->create();
        $article = Article::factory()->create()->first();
        $commentBody = $this->faker->realTextBetween(50, 100);
        $comment = new Comment();
        $comment->body = $commentBody;
        $comment->subject = $article->slug;
        $comment->user_id = Auth::id();
        $comment->commentable_type = Article::class;
        $comment->commentable_id = $article->id;

        $this->post(route('api.create-comment.article', ['slug' =>  $article->slug]),
            ['comment' => $commentBody])
            ->assertStatus(201)
            ->assertJson(['success' => 'success']);
        $this->assertDatabaseHas('comments', $comment->toArray());
    }

    public function testCreateArticleValidationBodyField()
    {
        $this->actingAsSanctumUser();
//        $this->withoutExceptionHandling();
        Author::factory()->create();
        $data = [
            'title' => $this->faker->word,
            'body' => $this->faker->text(99),
            'test' => 'test'
        ];

        $this->post(route('api.create.article'), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertJson([
                    "error" => [
                            "body" => [ "The body must be at least 100 characters."]
                        ]
                    ]);

    }

    public function testUpdateArticle()
    {
        $this->actingAsSanctumUser();
        $this->withoutExceptionHandling();
        Author::factory()->create();
        $article = Article::factory()->create()->first();
        $title = $this->faker->sentence(6, true);
        $slug = Str::substr(Str::lower(preg_replace('/\s+/', '-', $title)), 0, -1);

        $data = [
            'title' => $title,
            'body' => $this->faker->realTextBetween(100, 150),
        ];
        $this->put(route('api.update.article', ['slug' => $article->slug]), $data)
            ->assertStatus(200)
            ->assertJson(['success' => 'success', 'slug' => $slug]);

        unset($data['test']);
        $this->assertDatabaseHas('articles', $data);
    }

    public function testDestroyArticle()
    {
        $this->actingAsSanctumUser();
        $this->withoutExceptionHandling();
        Author::factory()->create();
        $article = Article::factory()->create()->first();
        $this->delete(route('api.delete.article', $article->slug))
            ->assertStatus(200)
            ->assertJson(['success' => 'deleted']);
        $this->assertDatabaseMissing('articles', $article->toArray());
    }
}
