<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleRequest;
use App\Http\Services\Api\ArticleService;
use App\Models\Article;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ArticleController extends Controller
{

    private ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $articles = Article::paginate(5);

        return response()->json($articles, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleRequest $request
     *
     * @return JsonResponse
     */
    public function store(ArticleRequest $request): JsonResponse
    {
        $this->articleService->createArticle($request);
        return response()->json(['success' => 'success'], Response::HTTP_CREATED);
    }

    /**
     * Store article comment
     *
     * @param Article $article
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function storeComment(Article $article, Request $request): JsonResponse
    {
        $this->articleService->storeComment($article, $request);
        return response()->json(['success' => 'success'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     *
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        return response()->json($article, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Article $article
     * @param ArticleRequest $request
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Article $article, ArticleRequest $request): JsonResponse
    {
        $article = $this->articleService->updateArticle($request, $article);
        return response()->json(['success' => 'success', 'slug' => $article->slug], Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        return response()->json(['success' => 'deleted'], Response::HTTP_OK);
    }
}
