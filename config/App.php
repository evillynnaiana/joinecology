<?php

namespace Config;

class App
{
    public static array $middlewareAliases = [
        'auth' => \App\Middleware\Authenticate::class,
        'admin' => \App\Middleware\Admin::class,
        'guest' => \App\Middleware\RedirectIfAuthenticated::class
    ];
}
