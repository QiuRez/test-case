<?php

namespace App\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SuccessResponse
{
  public static function make(JsonResource $data, string $message, int $code = SymfonyResponse::HTTP_OK): JsonResponse
  {
    return response()->json(['status' => 'success', 'message' => $message, 'data' => $data], $code);
  }
}