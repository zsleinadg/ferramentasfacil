<?php

class GuestMiddleware
{
    public function handle(): bool
    {
        if (isset($_SESSION['userId'])) {
            redirect('/');
            return false;
        }
        return true;
    }
}
