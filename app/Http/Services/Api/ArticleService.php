<?php

namespace App\Http\Services\Api;

use App\Models\Article;
use App\Models\Author;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ArticleService
{
    private function fileHandle(Request $request, Article $article): Article
    {
        $image = $request->file('image');
        $name = $image->store( '', ['public']);
        $image->move(storage_path('app/public/images/article-images'), $name);
        $article->image = '/images/article-images/' . $name;

        return $article;
    }

    private function addTag(Request $request, $article)
    {
        if ($request->tag_id)
        {
            $tag = Tag::findOrFail($request->tag_id);
            $article->tags()->sync($tag->id);
        }
    }

    public function createArticle(Request $request)
    {
        $author = Author::where('user_id', Auth::id())->first();
        $article = Article::make(array_merge($request->validated(), [
            'slug' => Str::substr(
                Str::lower(preg_replace('/\s+/', '-', $request->title)), 0, -1),
            'author_id' => $author->id
        ]));
        $this->fileHandle($request, $article)->save();
        $this->addTag($request, $article);
    }

    public function updateArticle(Request $request, $article)
    {
        $article->update(
            [
                'title' => $request->title,
                'slug' => Str::substr(
                    Str::lower(preg_replace('/\s+/', '-', $request->title)), 0, -1),
                'body' => $request->body
            ]
        );
        if ($request->image) {
            $this->fileHandle($request, $article)->save();
        }

        return $article;
    }


    public function storeComment(Article $article, Request $request)
    {
        $comment = new Comment();
        $comment->body = $request->comment;
        $comment->subject = $article->slug;
        $comment->user_id = Auth::id();
        $article->comments()->save($comment);
    }
}
