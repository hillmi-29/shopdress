<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'midtrans/webhook',
            'api/midtrans/webhook',
        ]);

        // API yang dilayani dari domain stateful (host web sendiri) memakai
        // session + cookie, sehingga Blade frontend dapat memakai endpoint
        // /api/* (wishlist, cart) dengan auth session tanpa token Bearer.
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport(AuthenticationException::class);

        // Let ValidationException be handled by Laravel's default handler (redirect with errors)
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            return null;
        });

        // Handle AuthenticationException natively (redirect for web, 401 for API)
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                    'code' => 401,
                ], 401);
            }

            return redirect()->guest($e->redirectTo($request) ?? route('login'));
        });

        $exceptions->renderable(function (HttpException $e, $request) {
            // Skip ValidationException - let Laravel handle it
            if ($e instanceof ValidationException) {
                return null;
            }

            $statusCode = $e->getStatusCode();

            // Only render custom views for known error codes, otherwise fallback to 500
            $supportedCodes = [403, 404, 419, 429, 500, 503];
            $viewCode = in_array($statusCode, $supportedCodes) ? $statusCode : 500;

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage() ?: 'Terjadi kesalahan.',
                    'code' => $viewCode,
                ], $viewCode);
            }

            return response()->view("errors.{$viewCode}", [], $viewCode);
        });

        // Fallback for any other exception (but not ValidationException or AuthenticationException)
        $exceptions->renderable(function (Throwable $e, $request) {
            // Skip exceptions that should be handled by Laravel's default handler
            if ($e instanceof ValidationException || $e instanceof AuthenticationException) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan server internal.',
                    'code' => 500,
                ], 500);
            }

            return response()->view('errors.500', [], 500);
        });
    })->create();
