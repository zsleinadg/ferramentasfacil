<?php

class Router
{
    private static array $routes = [];
    private static $notFoundHandler = null;
    private static array $middleware = [];

    public static function get(string $path, string $handler, array $middleware = []): void
    {
        self::addRoute('GET', $path, $handler, $middleware);
    }

    public static function post(string $path, string $handler, array $middleware = []): void
    {
        self::addRoute('POST', $path, $handler, $middleware);
    }

    public static function put(string $path, string $handler, array $middleware = []): void
    {
        self::addRoute('PUT', $path, $handler, $middleware);
    }

    public static function delete(string $path, string $handler, array $middleware = []): void
    {
        self::addRoute('DELETE', $path, $handler, $middleware);
    }

    private static function addRoute(string $method, string $path, string $handler, array $middleware): void
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        self::$routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public static function notFound(callable $handler): void
    {
        self::$notFoundHandler = $handler;
    }

    public static function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                foreach ($route['middleware'] as $middlewareEntry) {
                    $middlewareClass = is_array($middlewareEntry) ? $middlewareEntry[0] : $middlewareEntry;
                    $middlewareParams = is_array($middlewareEntry) ? ($middlewareEntry[1] ?? []) : [];

                    if (!class_exists($middlewareClass)) {
                        require basePath('app/Middleware/' . $middlewareClass . '.php');
                    }
                    $middleware = new $middlewareClass(...$middlewareParams);
                    $result = $middleware->handle();
                    if ($result === false) {
                        return;
                    }
                }

                $handlerParts = explode('@', $route['handler']);
                $controllerName = $handlerParts[0];
                $methodName = $handlerParts[1] ?? 'index';

                $controllerFile = basePath("app/Controllers/{$controllerName}.php");
                if (!file_exists($controllerFile)) {
                    throw new RuntimeException("Controller not found: {$controllerName}");
                }

                require_once $controllerFile;
                $controller = new $controllerName();

                $params = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
                $controller->$methodName(...array_values($params));
                return;
            }
        }

        if (self::$notFoundHandler) {
            $handler = self::$notFoundHandler;
            $handler();
        } else {
            abort(404);
        }
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }
}
