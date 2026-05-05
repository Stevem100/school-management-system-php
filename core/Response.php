<?php

declare(strict_types=1);

namespace Core;

/**
 * Response Helpers
 *
 * Provides methods for returning JSON responses, redirects,
 * and rendering PHP view files.
 */
class Response
{
    /**
     * Return a JSON response.
     *
     * @param mixed $data   Data to encode as JSON
     * @param int   $status HTTP status code (default: 200)
     * @return void
     */
    public static function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        $output = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        echo $output;
        exit;
    }

    /**
     * Return a JSON success response.
     *
     * @param mixed  $data    Response data
     * @param string $message Success message
     * @param int    $status  HTTP status code
     * @return void
     */
    public static function success($data = null, string $message = 'Success', int $status = 200): void
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        self::json($response, $status);
    }

    /**
     * Return a JSON error response.
     *
     * @param string $message Error message
     * @param int    $status  HTTP status code
     * @param mixed  $errors  Additional error details
     * @return void
     */
    public static function error(string $message = 'Error', int $status = 400, $errors = null): void
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        self::json($response, $status);
    }

    /**
     * Return a JSON validation error response.
     *
     * @param array $errors Associative array of field => error messages
     * @return void
     */
    public static function validationError(array $errors): void
    {
        self::error('Validation failed', 422, $errors);
    }

    /**
     * Return a JSON "not found" response.
     *
     * @param string $message Not found message
     * @return void
     */
    public static function notFound(string $message = 'Resource not found'): void
    {
        self::error($message, 404);
    }

    /**
     * Return a JSON "unauthorized" response.
     *
     * @param string $message Unauthorized message
     * @return void
     */
    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::error($message, 401);
    }

    /**
     * Return a JSON "forbidden" response.
     *
     * @param string $message Forbidden message
     * @return void
     */
    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::error($message, 403);
    }

    /**
     * Redirect to a URL.
     *
     * @param string $url Target URL (can be relative or absolute)
     * @return void
     */
    public static function redirect(string $url): void
    {
        // If relative path, make it absolute
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            $baseUrl = config('app_url', '');
            if ($baseUrl !== '' && $baseUrl !== null) {
                $url = rtrim($baseUrl, '/') . '/' . ltrim($url, '/');
            }
        }

        header('Location: ' . $url);
        exit;
    }

    /**
     * Redirect back to the previous page.
     *
     * @return void
     */
    public static function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirect($referer);
    }

    /**
     * Render a PHP view file with data extraction.
     *
     * View files are located in the `views/` directory.
     * Data is extracted as variables available in the view.
     *
     * @param string $path View path relative to views/ (without .php extension)
     * @param array  $data Associative array of data to pass to the view
     * @return void
     */
    public static function view(string $path, array $data = []): void
    {
        // Convert dot notation to directory separators
        $viewFile = dirname(__DIR__) . '/views/' . str_replace('.', '/', $path) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            if (config('debug', false)) {
                echo '<h1>View Not Found</h1>';
                echo '<p>View file: <code>' . e($viewFile) . '</code></p>';
            } else {
                echo '<h1>500 Internal Server Error</h1>';
            }
            exit;
        }

        // Extract data variables into the view scope
        extract($data, EXTR_SKIP);

        // Start output buffering
        ob_start();

        require $viewFile;

        // Capture and output the buffered content
        $content = ob_get_clean();
        echo $content;
        exit;
    }

    /**
     * Set a response header.
     *
     * @param string $name   Header name
     * @param string $value  Header value
     * @param bool   $replace Whether to replace existing header
     * @return void
     */
    public static function header(string $name, string $value, bool $replace = true): void
    {
        header($name . ': ' . $value, $replace);
    }

    /**
     * Set the HTTP status code.
     *
     * @param int $code HTTP status code
     * @return void
     */
    public static function status(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Download a file as a response.
     *
     * @param string $filePath     Path to the file
     * @param string $downloadName Suggested file name for download
     * @return void
     */
    public static function download(string $filePath, string $downloadName = ''): void
    {
        if (!file_exists($filePath)) {
            self::notFound('File not found');
            return;
        }

        if ($downloadName === '') {
            $downloadName = basename($filePath);
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }
}
