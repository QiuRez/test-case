<?php

namespace App\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ErrorResponse
{
  public static function make(string $message, int $code = SymfonyResponse::HTTP_BAD_REQUEST)
  {
    return response()->json(['status' => 'error', 'message' => $message], $code);
  }
}