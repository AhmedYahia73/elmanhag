<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AffilateMiddleware;
use App\Http\Middleware\ParentMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
            Route::middleware('api')
                ->prefix('student')
                ->name('student.')
                ->group(base_path('routes/student.php'));
            Route::middleware('api')
                ->prefix('affilate')
                ->name('affilate.')
                ->group(base_path('routes/affilate.php'));
            Route::middleware('api')
                ->prefix('parent')
                ->name('parent.')
                ->group(base_path('routes/parent.php'));
            Route::middleware(['api',TeacherMiddleware::class])
                ->prefix('teacher')
                ->name('teacher.')
                ->group(base_path('routes/teacher.php'));
        },
    )

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'IsStudent' => StudentMiddleware::class,
            'IsAdmin' => AdminMiddleware::class,
            'IsAffilate' => AffilateMiddleware::class,
            'IsParent' => ParentMiddleware::class,
            'IsTeacher  ' => TeacherMiddleware::class,
        ]);
         $middleware->redirectGuestsTo(function (Request $request) {
            if (!$request->is('api/*')) {
                return route('unauthorized');
            } 
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }
        });
    })->create();
