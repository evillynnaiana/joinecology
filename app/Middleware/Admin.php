<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class Admin implements Middleware
{
    public function handle(Request $request): void
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            FlashMessage::danger('Acesso negado: apenas administradores podem acessar esta área.');
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
}
