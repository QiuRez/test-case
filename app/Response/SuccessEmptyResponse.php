<?php

namespace App\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SuccessEmptyResponse
{
  public static function make(string $message, int $code = SymfonyResponse::HTTP_OK)
  {
    return response()->json(['status' => 'success', 'message' => $message], $code);
  }
}