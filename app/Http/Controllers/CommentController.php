<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentDeleteRequest;
use App\Http\Requests\CommentIndexRequest;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Response\ErrorResponse;
use App\Response\SuccessEmptyResponse;
use App\Response\SuccessResponse;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    private CommentService $commentService;
    
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CommentIndexRequest $request): JsonResponse
    {
        $comment = Comment::isActive()->orderBy('created_at')->paginate(12, page: $request->get('page', 1));
        return SuccessResponse::make(CommentResource::collection($comment), 'Comment list');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request): JsonResponse
    {
        $comment = Comment::create($request->validated());

        $this->commentService->store($comment);

        return SuccessResponse::make(CommentResource::make($comment), 'Comment created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        if (!$comment->is_active && !optional(auth()->user())->is_admin) return ErrorResponse::make('Comment not active');
        return SuccessResponse::make(CommentResource::make($comment), 'Show comment');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, Comment $comment): JsonResponse
    {
        $comment->update($request->validated());
        return SuccessResponse::make(CommentResource::make($comment), 'Success updated comment');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentDeleteRequest $request, Comment $comment): JsonResponse
    {
        $comment->forceDelete();
        return SuccessEmptyResponse::make('Comment success delete');
    }
}
