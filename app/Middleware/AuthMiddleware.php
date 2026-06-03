<?php

class AuthMiddleware
{
    public function handle(): bool
    {
        if (!isset($_SESSION['userId'])) {
            $_SESSION['_flash']['error'] = 'Faça login para acessar esta página.';
            redirect('/login');
            return false;
        }
        return true;
    }
}
