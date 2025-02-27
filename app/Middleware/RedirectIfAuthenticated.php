<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class RedirectIfAuthenticated implements Middleware
{
    public function handle(Request $request): void
    {
        if (Auth::check()) {
            FlashMessage::warning('Você já está logado!');
            header('Location: ' . route('root'));
            exit;
        }
    }
}
