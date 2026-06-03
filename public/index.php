<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$publicPath = __DIR__ . $uri;
if ($uri !== '/' && file_exists($publicPath) && !is_dir($publicPath)) {
    return false;
}

require_once dirname(__DIR__) . '/app/Helpers.php';

require_once dirname(__DIR__) . '/app/Router.php';

$app = require dirname(__DIR__) . '/config/app.php';

$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

session_set_cookie_params([
    'lifetime' => $app['session_lifetime'],
    'path' => '/',
    'domain' => '',
    'secure' => $isSecure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

spl_autoload_register(function (string $class) {
    $paths = [
        dirname(__DIR__) . '/app/Controllers/',
        dirname(__DIR__) . '/app/Models/',
        dirname(__DIR__) . '/app/Middleware/',
        dirname(__DIR__) . '/app/Helpers/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

if ($app['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

if (!isset($_SESSION['_csrf_token'])) {
    $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
}

require_once dirname(__DIR__) . '/routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

$uri = $_SERVER['REQUEST_URI'];

Router::dispatch($method, $uri);
