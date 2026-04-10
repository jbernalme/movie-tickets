<?php

use App\Exceptions\PaymentPlatformNotConfiguredException;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(
            append: [
                HandleAppearance::class,
                HandleInertiaRequests::class,
                AddLinkHeadersForPreloadedAssets::class,
            ],
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (
            Response $response,
            Throwable $e,
            Request $request,
        ) {
            logger('env: ' . app()->environment());
            logger('status: ' . $response->getStatusCode());
            logger(
                'es local: ' .
                    (app()->environment('local', 'testing') ? 'si' : 'no'),
            );

            if (app()->environment('local', 'testing')) {
                return $response;
            }

            logger('pasó el guard');

            $status = $response->getStatusCode();

            logger('va a renderizar: error-page con status ' . $status);

            if (in_array($status, [403, 404, 500, 503])) {
                $rendered = Inertia::render('error-page', ['status' => $status])
                    ->toResponse($request)
                    ->setStatusCode($status);

                logger('renderizado correctamente');

                return $rendered;
            }

            return $response;
        });
    })
    ->create();
