<?php

namespace App\Http;

use App\Http\Middleware\CheckCookie;
use App\Http\Middleware\checkLoginToken;
use App\Http\Middleware\CheckUid;
use App\Http\Middleware\clickLog;
use App\Http\Middleware\endTest;
use App\Http\Middleware\checkIp;
use App\Http\Middleware\jiami;
use App\Http\Middleware\miyao;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'log.click' =>[
            clickLog::class
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'check'=> \App\Http\Middleware\checkIp::class,
        'jiami'=> \App\Http\Middleware\jiami::class,
        'miyao'=> \App\Http\Middleware\miyao::class,
        'encode'=> \App\Http\Middleware\encode::class,
        'fs'=> \App\Http\Middleware\fangshua::class,
        'token'=> \App\Http\Middleware\checklogin::class,
        'check.uid' => CheckUid::class,
        'check.cookie'  => CheckCookie::class,
        'check.login.token' => checkLoginToken::class,
        'res.end'       => endTest::class,

    ];
}
