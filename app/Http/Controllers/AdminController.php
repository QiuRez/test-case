<?php 

namespace App\Http\Controllers;

use App\Http\Requests\AdminSetIsActiveRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Response\SuccessEmptyResponse;
use Illuminate\Http\JsonResponse;


class AdminController extends Controller
{
  public function setIsActive(AdminSetIsActiveRequest $request): JsonResponse
  {
    $status = $request->get('isActiveStatus');

    if ($posts = $request->get('posts')) {
      Post::whereIn('id', $posts)->update(['is_active' => $status]);
    }
    if ($comments = $request->get('comments')) {
      Comment::whereIn('id', $comments)->update(['is_active' => $status]);
    }

    return SuccessEmptyResponse::make('Data success updated');
  }
}