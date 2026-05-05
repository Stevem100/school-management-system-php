<?php

declare(strict_types=1);

namespace Core;

/**
 * Simple Laravel-like Router
 *
 * Supports GET, POST, PUT, DELETE methods, route parameters (/users/{id}),
 * route groups with prefixes, and controller@method handler strings.
 */
class Router
{
    /** @var array<string, array> Registered routes grouped by HTTP method */
    private static array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'PATCH'  => [],
        'DELETE' => [],
    ];

    /** @var string Current group prefix (used during group registration) */
    private static string $groupPrefix = '';

    /** @var array<string, string> Extracted parameters from the matched route */
    private static array $currentParams = [];

    /** @var string|null The matched route's handler */
    private static ?string $currentHandler = null;

    /**
     * Register a GET route.
     *
     * @param string   $path    URI path pattern (e.g. '/users/{id}')
     * @param mixed    $handler Closure or 'Controller@method' string
     * @return void
     */
    public static function get(string $path, $handler): void
    {
        self::addRoute('GET', $path, $handler);
    }

    /**
     * Register a POST route.
     *
     * @param string   $path    URI path pattern
     * @param mixed    $handler Closure or 'Controller@method' string
     * @return void
     */
    public static function post(string $path, $handler): void
    {
        self::addRoute('POST', $path, $handler);
    }

    /**
     * Register a PUT route.
     *
     * @param string   $path    URI path pattern
     * @param mixed    $handler Closure or 'Controller@method' string
     * @return void
     */
    public static function put(string $path, $handler): void
    {
        self::addRoute('PUT', $path, $handler);
    }

    /**
     * Register a PATCH route.
     *
     * @param string   $path    URI path pattern
     * @param mixed    $handler Closure or 'Controller@method' string
     * @return void
     */
    public static function patch(string $path, $handler): void
    {
        self::addRoute('PATCH', $path, $handler);
    }

    /**
     * Register a DELETE route.
     *
     * @param string   $path    URI path pattern
     * @param mixed    $handler Closure or 'Controller@method' string
     * @return void
     */
    public static function delete(string $path, $handler): void
    {
        self::addRoute('DELETE', $path, $handler);
    }

    /**
     * Register a group of routes with a shared prefix.
     *
     * @param string   $prefix   URL prefix for all routes in the group
     * @param callable $callback Closure that registers routes within the group
     * @return void
     */
    public static function group(string $prefix, callable $callback): void
    {
        $previousPrefix = self::$groupPrefix;
        self::$groupPrefix = $previousPrefix . '/' . trim($prefix, '/');

        $callback();

        self::$groupPrefix = $previousPrefix;
    }

    /**
     * Add a route to the registry.
     *
     * @param string $method  HTTP method
     * @param string $path    URI path pattern
     * @param mixed  $handler Route handler
     * @return void
     */
    private static function addRoute(string $method, string $path, $handler): void
    {
        // Apply current group prefix
        $fullPath = self::$groupPrefix . '/' . trim($path, '/');
        $fullPath = '/' . trim($fullPath, '/');

        // Normalize: ensure root path is just '/'
        if ($fullPath !== '/') {
            $fullPath = rtrim($fullPath, '/');
        }

        self::$routes[$method][$fullPath] = [
            'handler'  => $handler,
            'pattern'  => self::buildRegexPattern($fullPath),
            'original' => $fullPath,
        ];
    }

    /**
     * Build a regex pattern from a route path with parameter placeholders.
     *
     * Converts '/users/{id}' to '#^/users/(?P<id>[^/]+)$#'
     *
     * @param string $path Route path with {param} placeholders
     * @return string      Regular expression pattern
     */
    private static function buildRegexPattern(string $path): string
    {
        // Escape forward slashes for regex
        $pattern = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            function (array $matches): string {
                return '(?P<' . $matches[1] . '>[^/]+)';
            },
            $path
        );

        return '#^' . $pattern . '$#';
    }

    /**
     * Dispatch the current request to the matching route handler.
     *
     * Parses the request URI, matches it against registered routes,
     * extracts parameters, and invokes the handler.
     *
     * @return mixed  Return value from the handler
     */
    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Handle PUT/PATCH sent as POST with _method override
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        // Parse the request URI, stripping query string
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = '/' . trim((string) $uri, '/');

        // Normalize empty path to '/'
        if ($uri === '') {
            $uri = '/';
        }

        // Check for matching route
        if (!isset(self::$routes[$method])) {
            return self::handleNotFound($uri);
        }

        foreach (self::$routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters (filter out numeric keys)
                self::$currentParams = array_filter(
                    $matches,
                    fn($key) => is_string($key),
                    ARRAY_FILTER_USE_KEY
                );

                self::$currentHandler = is_string($route['handler'])
                    ? $route['handler']
                    : 'Closure';

                return self::invokeHandler($route['handler']);
            }
        }

        return self::handleNotFound($uri);
    }

    /**
     * Invoke the matched route handler.
     *
     * @param mixed $handler Closure or 'Controller@method' string
     * @return mixed         Handler return value
     */
    private static function invokeHandler($handler)
    {
        // Handler is a closure
        if ($handler instanceof \Closure) {
            return call_user_func_array($handler, self::$currentParams);
        }

        // Handler is a 'Controller@method' string
        if (is_string($handler) && str_contains($handler, '@')) {
            [$controller, $method] = explode('@', $handler, 2);

            // Determine the fully qualified controller class
            $controllerClass = self::resolveControllerClass($controller);

            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller not found: {$controllerClass}");
            }

            $instance = new $controllerClass();

            if (!method_exists($instance, $method)) {
                throw new \RuntimeException("Method {$method} not found on controller {$controllerClass}");
            }

            return call_user_func_array([$instance, $method], self::$currentParams);
        }

        // Handler is a callable array (e.g., [Controller::class, 'method'])
        if (is_array($handler) && is_callable($handler)) {
            return call_user_func_array($handler, self::$currentParams);
        }

        throw new \RuntimeException('Invalid route handler: ' . print_r($handler, true));
    }

    /**
     * Resolve a controller class name to its fully qualified namespace.
     *
     * @param string $controller Controller short name (e.g. 'AuthController')
     * @return string            Fully qualified class name
     */
    private static function resolveControllerClass(string $controller): string
    {
        // If already has a namespace, return as-is
        if (str_contains($controller, '\\')) {
            return $controller;
        }

        // Try App\Controllers namespace first, then global
        $namespaces = [
            'App\\Controllers\\',
            'Controllers\\',
            '',
        ];

        foreach ($namespaces as $ns) {
            $class = $ns . $controller;
            if (class_exists($class)) {
                return $class;
            }
        }

        // Default namespace
        return 'App\\Controllers\\' . $controller;
    }

    /**
     * Handle 404 Not Found.
     *
     * @param string $uri Requested URI
     * @return void
     */
    private static function handleNotFound(string $uri): void
    {
        http_response_code(404);

        // Try to render a 404 view, otherwise output JSON
        $viewPath = dirname(__DIR__) . '/views/errors/404.php';
        if (file_exists($viewPath)) {
            extract(['uri' => $uri]);
            require $viewPath;
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Route not found',
                'path'    => $uri,
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get all registered routes.
     *
     * @return array<string, array>
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Get the currently matched route parameters.
     *
     * @return array<string, string>
     */
    public static function getParams(): array
    {
        return self::$currentParams;
    }

    /**
     * Get the current matched handler string.
     *
     * @return string|null
     */
    public static function getCurrentHandler(): ?string
    {
        return self::$currentHandler;
    }

    /**
     * Check if a specific route exists.
     *
     * @param string $method HTTP method
     * @param string $path   URI path
     * @return bool
     */
    public static function hasRoute(string $method, string $path): bool
    {
        $method = strtoupper($method);
        return isset(self::$routes[$method][$path]);
    }
}
