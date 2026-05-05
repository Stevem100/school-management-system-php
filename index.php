<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Entry Point — Front Controller
|--------------------------------------------------------------------------
|
| This file is the single entry point for all HTTP requests.
| It bootstraps the application, loads core files, starts the session,
| registers routes, and dispatches the request to the appropriate handler.
|
*/

// ─── Load .env file ─────────────────────────────────────────────────────────

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove surrounding quotes
            if (preg_match('/^["\'](.*)["\']\s*$/', $value, $matches)) {
                $value = $matches[1];
            }
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// ─── Error Reporting ─────────────────────────────────────────────────────────

// Load config early for debug mode
$configPath = __DIR__ . '/config/app.php';
$appConfig = file_exists($configPath) ? require $configPath : [];
$debug = $appConfig['debug'] ?? false;

if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// ─── Timezone ────────────────────────────────────────────────────────────────

$timezone = $appConfig['timezone'] ?? 'UTC';
date_default_timezone_set($timezone);

// ─── Core Autoloader (Manual — No Composer) ──────────────────────────────────

$coreFiles = [
    __DIR__ . '/core/helpers.php',
    __DIR__ . '/core/Database.php',
    __DIR__ . '/core/Request.php',
    __DIR__ . '/core/Response.php',
    __DIR__ . '/core/Session.php',
    __DIR__ . '/core/Auth.php',
    __DIR__ . '/core/Controller.php',
    __DIR__ . '/core/Router.php',
];

foreach ($coreFiles as $file) {
    if (file_exists($file)) {
        require_once $file;
    } else {
        http_response_code(500);
        echo 'Critical Error: Missing core file — ' . basename($file);
        exit;
    }
}

// ─── Simple PSR-4 Compatible Autoloader ──────────────────────────────────────
// Maps App\Controllers\ClassName → controllers/ClassName.php
// Maps App\Models\ClassName → models/ClassName.php

spl_autoload_register(function (string $class): void {
    $prefixes = [
        'App\\Controllers\\' => __DIR__ . '/app/Controllers/',
        'App\\Models\\'      => __DIR__ . '/app/Models/',
        'App\\Middleware\\'  => __DIR__ . '/app/Middleware/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relativeClass = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// ─── Start Session ───────────────────────────────────────────────────────────

\Core\Session::start();

// ─── Initialize CSRF Token ───────────────────────────────────────────────────

csrf_token();

// ─── Route Registration ──────────────────────────────────────────────────────

// Include route definitions
$webRoutes = __DIR__ . '/routes/web.php';
$apiRoutes = __DIR__ . '/routes/api.php';

if (file_exists($webRoutes)) {
    require_once $webRoutes;
} else {
    error_log('Warning: Web routes file not found at ' . $webRoutes);
}

if (file_exists($apiRoutes)) {
    require_once $apiRoutes;
} else {
    error_log('Warning: API routes file not found at ' . $apiRoutes);
}

// ─── Dispatch the Request ───────────────────────────────────────────────────

try {
    \Core\Router::dispatch();
} catch (\RuntimeException $e) {
    // Handle routing/database errors
    http_response_code(500);

    if ($debug) {
        echo '<h1>Application Error</h1>';
        echo '<p><strong>Message:</strong> ' . e($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . e($e->getFile()) . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';

        if ($debug) {
            echo '<h3>Stack Trace</h3>';
            echo '<pre>' . e($e->getTraceAsString()) . '</pre>';
        }
    } else {
        // Check if the request expects JSON
        if (\Core\Request::expectsJson()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error.',
            ], JSON_PRETTY_PRINT);
        } else {
            echo '<h1>500 Internal Server Error</h1>';
            echo '<p>Something went wrong. Please try again later.</p>';
        }
    }

    // Log the error
    error_log('Application Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
} catch (\Throwable $e) {
    // Handle all other errors
    http_response_code(500);

    if ($debug) {
        echo '<h1>Fatal Error</h1>';
        echo '<p><strong>Message:</strong> ' . e($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . e($e->getFile()) . '</p>';
        echo '<p><strong>Line:</strong> ' . $e->getLine() . '</p>';
    } else {
        if (\Core\Request::expectsJson()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error.',
            ], JSON_PRETTY_PRINT);
        } else {
            echo '<h1>500 Internal Server Error</h1>';
        }
    }

    error_log('Fatal Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
}
