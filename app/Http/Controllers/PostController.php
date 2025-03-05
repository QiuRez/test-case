<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostDestroyRequest;
use App\Http\Requests\PostIndexRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Response\ErrorResponse;
use App\Response\SuccessEmptyResponse;
use App\Response\SuccessResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(PostIndexRequest $request): JsonResponse
    {
        $posts = Post::isActive()->with('comments', function($query) {
            $query->isActive()->limit(2);
        })
        ->orderBy('created_at')
        ->paginate(perPage: 12, page: $request->get('page', 1));

        return SuccessResponse::make(PostResource::collection($posts), 'Post list');
    }


    public function show(Post $post): JsonResponse
    {
        if (!$post->is_active && !optional(auth()->user())->is_admin) return ErrorResponse::make('Post not active');
        return SuccessResponse::make(PostResource::make($post), 'Success fetch post');
    }

    public function store(PostCreateRequest $request): JsonResponse
    {
        $post = Post::create([
            ...$request->validated(),
            'user_id' => $request->user()->id
        ]);

        return SuccessResponse::make(PostResource::make($post), 'Post success created');
    }

    public function destroy(PostDestroyRequest $request, Post $post): JsonResponse
    {
        $post->forceDelete();
        return SuccessEmptyResponse::make('Пост удалён');
    }

    public function update(PostUpdateRequest $request, Post $post): JsonResponse
    {
        $post->update([
            ...$request->validated(),
            'is_active' => $request->get('is_active') ? auth()->user()->is_admin : false
        ]);
        return SuccessEmptyResponse::make('Post successfully updated');
    }

}
