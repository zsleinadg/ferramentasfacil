<?php

function env(string $key, mixed $default = null): mixed
{
    $value = getenv($key);
    if ($value !== false) {
        if ($value === 'true') return true;
        if ($value === 'false') return false;
        if ($value === 'null') return null;
        return $value;
    }

    static $env = null;

    if ($env === null) {
        $envFile = dirname(__DIR__) . '/.env';
        if (!file_exists($envFile)) {
            return $default;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $envKey = trim($parts[0]);
                $envValue = trim($parts[1]);

                $envValue = trim($envValue, '"\'');
                if ($envValue === 'true') $envValue = true;
                elseif ($envValue === 'false') $envValue = false;
                elseif ($envValue === 'null') $envValue = null;

                $env[$envKey] = $envValue;
            }
        }
    }

    return $env[$key] ?? $default;
}

function basePath(string $path = ''): string
{
    return dirname(__DIR__) . ($path ? '/' . ltrim($path, '/') : '');
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function abort(int $code = 404): void
{
    http_response_code($code);
    $view = basePath("app/Views/errors/{$code}.php");
    if (file_exists($view)) {
        require $view;
    }
    exit;
}

function view(string $view, array $data = []): void
{
    extract($data);
    require basePath("app/Views/{$view}.php");
}

function methodField(string $method): string
{
    return '<input type="hidden" name="_method" value="' . $method . '">';
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old_input'][$key] ?? $default;
}

function csrfField(): string
{
    $token = $_SESSION['_csrf_token'] ?? '';
    return '<input type="hidden" name="_csrf_token" value="' . $token . '">';
}

function csrfVerify(): bool
{
    $token = $_POST['_csrf_token'] ?? '';
    $sessionToken = $_SESSION['_csrf_token'] ?? '';
    return hash_equals($sessionToken, $token);
}

function imageUrl(?string $path): string
{
    if (empty($path)) {
        return '';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return '/' . ltrim($path, '/');
}
