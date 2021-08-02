<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\AuthorService;
use App\Models\Article;
use App\Models\Author;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{

    private AuthorService $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $articles = Author::with('avatar')->paginate(5);
        return response()->json($articles, Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request):JsonResponse
    {
        $request->merge(['user_id' => Auth::id()]);
        $validator = $this->authorService->validateAuthor($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else {
            $this->authorService->createAuthor($request);
            return response()->json(['success' => 'success'], Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show():JsonResponse
    {
        $author = Author::where('user_id', Auth::id())->first();
        return response()->json($author, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validator = $this->authorService->validateAuthorUpdate($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else {
            $this->authorService->updateAuthor($request);
            return response()->json(['success' => 'updated'], Response::HTTP_OK);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return
     */
    public function destroy(): JsonResponse
    {
        Author::where('user_id', Auth::id())->delete();
        return response()->json(['success' => 'deleted'], Response::HTTP_OK);
    }
}
