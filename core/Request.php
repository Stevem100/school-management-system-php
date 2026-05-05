<?php

declare(strict_types=1);

namespace Core;

/**
 * Request Wrapper
 *
 * Provides a clean interface for accessing HTTP request data
 * including method, path, input, headers, and more.
 */
class Request
{
    /** @var array|null Cached parsed JSON body */
    private static ?array $jsonBody = null;

    /**
     * Get the HTTP request method.
     *
     * @return string Uppercase method name (GET, POST, PUT, DELETE, etc.)
     */
    public static function method(): string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // Handle _method override for PUT/PATCH/DELETE via POST forms
        if ($method === 'POST') {
            $override = self::input('_method');
            if ($override !== null) {
                return strtoupper((string) $override);
            }
        }

        return strtoupper($method);
    }

    /**
     * Get the request path (without query string).
     *
     * @return string Request path, e.g. '/users/1'
     */
    public static function path(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = '/' . trim((string) $uri, '/');

        return $path === '' ? '/' : $path;
    }

    /**
     * Get the full request URI including query string.
     *
     * @return string Full URI
     */
    public static function fullUri(): string
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    /**
     * Get an input value from GET, POST, or JSON body.
     *
     * Priority: POST data → GET data → JSON body
     *
     * @param string $key     Input key
     * @param mixed  $default Default value if key not found
     * @return mixed          Input value or default
     */
    public static function input(string $key, $default = null)
    {
        // Check POST data first
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        // Check GET data
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        // Check JSON body
        $body = self::json();
        if ($body !== null && array_key_exists($key, $body)) {
            return $body[$key];
        }

        return $default;
    }

    /**
     * Get all input data merged from GET, POST, and JSON body.
     *
     * @return array All input data
     */
    public static function all(): array
    {
        $get = $_GET ?: [];
        $post = $_POST ?: [];
        $json = self::json() ?: [];

        return array_merge($get, $post, $json);
    }

    /**
     * Check if an input key exists.
     *
     * @param string $key Input key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset($_POST[$key]) || isset($_GET[$key]) || self::json() !== null && array_key_exists($key, self::json());
    }

    /**
     * Check if the request has a specific input key with a non-empty value.
     *
     * @param string $key Input key
     * @return bool
     */
    public static function filled(string $key): bool
    {
        $value = self::input($key);
        return $value !== null && $value !== '';
    }

    /**
     * Get only the specified input keys.
     *
     * @param array $keys Keys to extract
     * @return array      Filtered input data
     */
    public static function only(array $keys): array
    {
        return array_intersect_key(self::all(), array_flip($keys));
    }

    /**
     * Get all input except the specified keys.
     *
     * @param array $keys Keys to exclude
     * @return array      Filtered input data
     */
    public static function except(array $keys): array
    {
        return array_diff_key(self::all(), array_flip($keys));
    }

    /**
     * Get a request header value.
     *
     * @param string $key     Header name (case-insensitive)
     * @param mixed  $default Default value
     * @return mixed          Header value or default
     */
    public static function header(string $key, $default = null)
    {
        $key = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$key] ?? $default;
    }

    /**
     * Get the client's IP address.
     *
     * @return string IP address
     */
    public static function ip(): string
    {
        // Check for proxied IP first
        $headers = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP'];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Get the User-Agent string.
     *
     * @return string
     */
    public static function userAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Check if the request is an AJAX request.
     *
     * @return bool
     */
    public static function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Check if the request expects a JSON response.
     *
     * @return bool
     */
    public static function expectsJson(): bool
    {
        return self::isAjax()
            || str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
    }

    /**
     * Get the Bearer token from the Authorization header.
     *
     * @return string|null Token string or null
     */
    public static function bearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (str_starts_with(strtolower($header), 'bearer ')) {
            return trim(substr($header, 7));
        }

        // Also check for token in query string (useful for API testing)
        if (isset($_GET['token'])) {
            return (string) $_GET['token'];
        }

        return null;
    }

    /**
     * Parse and cache the JSON request body.
     *
     * @return array|null Parsed JSON body or null
     */
    public static function json(): ?array
    {
        if (self::$jsonBody !== null) {
            return self::$jsonBody;
        }

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (
            str_contains($contentType, 'application/json')
            || str_contains($contentType, 'application/vnd.api+json')
        ) {
            $raw = file_get_contents('php://input');
            if ($raw) {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    self::$jsonBody = $decoded;
                    return self::$jsonBody;
                }
            }
        }

        self::$jsonBody = null;
        return null;
    }

    /**
     * Get the uploaded files.
     *
     * @return array Uploaded files array
     */
    public static function files(): array
    {
        return $_FILES;
    }

    /**
     * Check if a file was uploaded with the given key.
     *
     * @param string $key File input name
     * @return bool
     */
    public static function hasFile(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    /**
     * Get a query string parameter.
     *
     * @param string $key     Query parameter key
     * @param mixed  $default Default value
     * @return mixed
     */
    public static function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get the referrer URL.
     *
     * @return string|null
     */
    public static function referer(): ?string
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }
}
