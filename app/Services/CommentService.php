<?php

namespace App\Services;

use App\Mail\SendNotificeNewComment;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CommentService
{
  public function store(Comment $comment)
  {
    try {
      Mail::to($comment->post->user)
        ->send(new SendNotificeNewComment($comment->post, $comment));
    } catch (Throwable $th) {
      Log::error($th);
    }
  }
}