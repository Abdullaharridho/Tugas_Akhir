<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([

            'admin' => \App\Http\Middleware\Admin::class,
            'user' => \App\Http\Middleware\User::class,
            'guru' => \App\Http\Middleware\Guru::class,
        ]);
    })
     ->withSchedule(function (Schedule $schedule) {
        
        $schedule->command('notifikasi:perizinan')->dailyAt('14:02');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
