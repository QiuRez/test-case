<?php

use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\PostBelongsToUserMiddleware;
use App\Http\Middleware\PostOwnerOrAdminMiddleware;
use App\Response\ErrorResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('admin')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => IsAdminMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // dd($exceptions);
        $exceptions->stopIgnoring(ValidationException::class);

        $exceptions->render(function (ValidationException $e) {
            return ErrorResponse::make($e->getMessage());
        });

        $exceptions->render(function (RouteNotFoundException $e) {
            if($e->getMessage() == 'Route [login] not defined.') {
                abort(403);
                // return ErrorResponse::make('Unauthorized', SymfonyResponse::HTTP_UNAUTHORIZED);
            }
        });


    })->create();
