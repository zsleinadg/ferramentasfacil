<?php

class RoleMiddleware
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(): bool
    {
        if (!isset($_SESSION['userId'])) {
            redirect('/login');
            return false;
        }

        if (!empty($this->allowedRoles) && !in_array($_SESSION['roleName'], $this->allowedRoles)) {
            abort(403);
            return false;
        }

        return true;
    }
}
