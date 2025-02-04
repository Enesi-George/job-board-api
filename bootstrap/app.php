<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if (
                $e instanceof AuthenticationException ||
                ($e instanceof RouteNotFoundException && strpos($e->getMessage(), 'login') !== false)
            ) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthenticated. Please login to proceed'
                ], Response::HTTP_UNAUTHORIZED);
            }

            if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
                return response()->json([
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                ], Response::HTTP_FORBIDDEN);
            }

            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 'failed',
                    'message' =>  'Requested resource is not found'
                ], Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 'failed',
                    'errors' => $e->validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($e instanceof ThrottleRequestsException) {
                return response()->json([
                    'status' => 'failed',
                    'message' => $e->getMessage()
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            if ($e instanceof TokenMismatchException) {
                return response()->json([
                    'status' => 'failed',
                    'message' =>  $e->getMessage()
                ], Response::HTTP_FORBIDDEN);
            }

            logger($e);
            return response()->json([
                'status' => 'failed',
                'message' =>  $e->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        });
    })->create();
