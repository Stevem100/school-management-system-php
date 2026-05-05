<?php

declare(strict_types=1);

/**
 * Utility / Helper Functions
 *
 * Global helper functions used throughout the application.
 * These are loaded automatically by the framework's entry point.
 */

// ─── Configuration ───────────────────────────────────────────────────────────

if (!function_exists('config')) {
    /**
     * Get a value from the application configuration.
     *
     * Supports dot notation: config('supabase.url') → $config['supabase']['url']
     *
     * @param string $key     Config key (dot notation supported)
     * @param mixed  $default Default value if key not found
     * @return mixed          Config value or default
     */
    function config(string $key, $default = null)
    {
        static $config = null;

        if ($config === null) {
            $configPath = dirname(__DIR__) . '/config/app.php';
            if (file_exists($configPath)) {
                $config = require $configPath;
            } else {
                $config = [];
            }
        }

        // Support dot notation
        $keys = explode('.', $key);
        $value = $config;

        foreach ($keys as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

// ─── String Utilities ────────────────────────────────────────────────────────

if (!function_exists('e')) {
    /**
     * Escape HTML special characters.
     *
     * @param string|null $string String to escape
     * @return string            Escaped string
     */
    function e(?string $string): string
    {
        return htmlspecialchars((string) $string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('slug')) {
    /**
     * Generate a URL-friendly slug from text.
     *
     * @param string $text Text to slugify
     * @return string      URL slug
     */
    function slug(string $text): string
    {
        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-zA-Z0-9]+/', '-', $text);
        $slug = trim($slug, '-');
        return strtolower($slug);
    }
}

if (!function_exists('toCamelCase')) {
    /**
     * Convert a snake_case string to camelCase.
     *
     * @param string $str snake_case string
     * @return string    camelCase string
     */
    function toCamelCase(string $str): string
    {
        return lcfirst(str_replace('_', '', ucwords($str, '_')));
    }
}

if (!function_exists('toSnakeCase')) {
    /**
     * Convert a camelCase string to snake_case.
     *
     * @param string $str camelCase string
     * @return string    snake_case string
     */
    function toSnakeCase(string $str): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
    }
}

if (!function_exists('truncate')) {
    /**
     * Truncate a string to a specified length.
     *
     * @param string $string   String to truncate
     * @param int    $length   Maximum length
     * @param string $suffix   Suffix to append when truncated
     * @return string          Truncated string
     */
    function truncate(string $string, int $length = 100, string $suffix = '...'): string
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        return substr($string, 0, $length) . $suffix;
    }
}

if (!function_exists('str_contains')) {
    /**
     * Polyfill for str_contains (PHP < 8.0).
     */
    function str_contains(string $haystack, string $needle): bool
    {
        return $needle !== '' && strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('str_starts_with')) {
    /**
     * Polyfill for str_starts_with (PHP < 8.0).
     */
    function str_starts_with(string $haystack, string $needle): bool
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

// ─── URL Utilities ───────────────────────────────────────────────────────────

if (!function_exists('url')) {
    /**
     * Generate a URL from a path.
     *
     * @param string $path Relative path
     * @return string      Full URL
     */
    function url(string $path = ''): string
    {
        $baseUrl = config('app_url', '');
        if ($baseUrl === '' || $baseUrl === null) {
            // Auto-detect base URL
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseUrl = $scheme . '://' . $host;
        }

        $baseUrl = rtrim($baseUrl, '/');
        $path = '/' . ltrim($path, '/');

        return $baseUrl . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * Generate a URL for an asset file.
     *
     * @param string $path Relative path to the asset
     * @return string      Full asset URL
     */
    function asset(string $path): string
    {
        return url('/public/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    /**
     * Send a redirect header and terminate.
     *
     * @param string $url Target URL
     * @return void
     */
    function redirect(string $url): void
    {
        $absolute = str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
        if (!$absolute) {
            $url = url($url);
        }
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('back')) {
    /**
     * Redirect back to the previous page.
     *
     * @return void
     */
    function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referer);
    }
}

// ─── Date / Time Utilities ───────────────────────────────────────────────────

if (!function_exists('formatDate')) {
    /**
     * Format a date string for display.
     *
     * @param string|null $date   Date string (any parseable format)
     * @param string      $format Output format (default: 'M d, Y')
     * @return string             Formatted date or 'N/A' if invalid
     */
    function formatDate(?string $date, string $format = 'M d, Y'): string
    {
        if ($date === null || $date === '') {
            return 'N/A';
        }

        try {
            $dt = new \DateTime($date);
            return $dt->format($format);
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format a date-time string for display.
     *
     * @param string|null $date   Date-time string
     * @param string      $format Output format (default: 'M d, Y H:i')
     * @return string             Formatted date-time or 'N/A'
     */
    function formatDateTime(?string $date, string $format = 'M d, Y H:i'): string
    {
        return formatDate($date, $format);
    }
}

if (!function_exists('formatMoney')) {
    /**
     * Format a monetary amount.
     *
     * @param float|null $amount   Monetary amount
     * @param string     $currency Currency code (default: 'KES')
     * @return string              Formatted money string
     */
    function formatMoney(?float $amount, string $currency = 'KES'): string
    {
        if ($amount === null) {
            return $currency . ' 0.00';
        }

        $formatted = number_format($amount, 2, '.', ',');

        $symbols = [
            'KES' => 'KES ',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];

        $symbol = $symbols[$currency] ?? $currency . ' ';

        return $symbol . $formatted;
    }
}

if (!function_exists('timeAgo')) {
    /**
     * Get a human-readable time ago string.
     *
     * @param string|null $date Date string
     * @return string           "2 hours ago", "3 days ago", etc.
     */
    function timeAgo(?string $date): string
    {
        if ($date === null) {
            return 'N/A';
        }

        $now = new \DateTime();
        $ago = new \DateTime($date);
        $diff = $now->getTimestamp() - $ago->getTimestamp();

        if ($diff < 60) {
            return 'Just now';
        }

        $intervals = [
            31536000 => 'year',
            2592000  => 'month',
            604800   => 'week',
            86400    => 'day',
            3600     => 'hour',
            60       => 'minute',
        ];

        foreach ($intervals as $seconds => $label) {
            $count = (int) floor($diff / $seconds);
            if ($count >= 1) {
                return $count . ' ' . $label . ($count > 1 ? 's' : '') . ' ago';
            }
        }

        return 'Just now';
    }
}

// ─── Authentication Helpers ──────────────────────────────────────────────────

if (!function_exists('isLoggedIn')) {
    /**
     * Check if the current user is logged in.
     *
     * @return bool
     */
    function isLoggedIn(): bool
    {
        $user = \Core\Session::get('user');
        $token = \Core\Session::get('token');
        return $user !== null && $token !== null;
    }
}

if (!function_exists('currentUser')) {
    /**
     * Get the currently authenticated user.
     *
     * @return array|null User data or null
     */
    function currentUser(): ?array
    {
        return \Core\Session::get('user');
    }
}

if (!function_exists('currentUserId')) {
    /**
     * Get the current user's ID.
     *
     * @return string|null
     */
    function currentUserId(): ?string
    {
        $user = currentUser();
        return $user['id'] ?? null;
    }
}

if (!function_exists('currentUserName')) {
    /**
     * Get the current user's display name.
     *
     * @return string User name or 'Guest'
     */
    function currentUserName(): string
    {
        $user = currentUser();
        if ($user === null) {
            return 'Guest';
        }
        return ($user['firstName'] ?? '') . ' ' . ($user['lastName'] ?? '');
    }
}

// ─── Flash Messages ──────────────────────────────────────────────────────────

if (!function_exists('flash')) {
    /**
     * Set a flash message.
     *
     * @param string $key   Flash key
     * @param string $value Flash message
     * @return void
     */
    function flash(string $key, string $value): void
    {
        \Core\Session::flash($key, $value);
    }
}

if (!function_exists('getFlash')) {
    /**
     * Get a flash message (keeps it for the next request).
     *
     * @param string $key     Flash key
     * @param string $default Default message
     * @return string         Flash message or default
     */
    function getFlash(string $key, string $default = ''): string
    {
        return (string) \Core\Session::getFlash($key, $default);
    }
}

if (!function_exists('success_msg')) {
    /**
     * Set a success flash message.
     *
     * @param string $msg Success message
     * @return void
     */
    function success_msg(string $msg): void
    {
        flash('success', $msg);
    }
}

if (!function_exists('error_msg')) {
    /**
     * Set an error flash message.
     *
     * @param string $msg Error message
     * @return void
     */
    function error_msg(string $msg): void
    {
        flash('error', $msg);
    }
}

if (!function_exists('warning_msg')) {
    /**
     * Set a warning flash message.
     *
     * @param string $msg Warning message
     * @return void
     */
    function warning_msg(string $msg): void
    {
        flash('warning', $msg);
    }
}

if (!function_exists('info_msg')) {
    /**
     * Set an info flash message.
     *
     * @param string $msg Info message
     * @return void
     */
    function info_msg(string $msg): void
    {
        flash('info', $msg);
    }
}

// ─── CSRF Protection ─────────────────────────────────────────────────────────

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF hidden input field.
     *
     * Uses the session token as the CSRF token for simplicity.
     *
     * @return string HTML hidden input element
     */
    function csrf_field(): string
    {
        $token = \Core\Session::get('_csrf_token');

        if ($token === null) {
            $token = bin2hex(random_bytes(32));
            \Core\Session::set('_csrf_token', $token);
        }

        return '<input type="hidden" name="_token" value="' . e($token) . '">';
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the current CSRF token.
     *
     * @return string CSRF token
     */
    function csrf_token(): string
    {
        $token = \Core\Session::get('_csrf_token');

        if ($token === null) {
            $token = bin2hex(random_bytes(32));
            \Core\Session::set('_csrf_token', $token);
        }

        return $token;
    }
}

if (!function_exists('verify_csrf')) {
    /**
     * Verify a CSRF token from the request.
     *
     * @return bool True if token is valid
     */
    function verify_csrf(): bool
    {
        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $stored = \Core\Session::get('_csrf_token');

        if ($stored === null || $token === '') {
            return false;
        }

        return hash_equals($stored, (string) $token);
    }
}

// ─── Debugging ───────────────────────────────────────────────────────────────

if (!function_exists('dd')) {
    /**
     * Dump and die — output variable content and terminate.
     *
     * @param mixed ...$vars Variables to dump
     * @return void
     */
    function dd(...$vars): void
    {
        echo '<pre style="background:#1e1e1e;color:#d4d4d4;padding:16px;border-radius:8px;margin:8px;overflow:auto;">';
        foreach ($vars as $var) {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
        }
        echo '</pre>';
        exit;
    }
}

if (!function_exists('dump')) {
    /**
     * Dump variable content without dying.
     *
     * @param mixed ...$vars Variables to dump
     * @return void
     */
    function dump(...$vars): void
    {
        echo '<pre style="background:#1e1e1e;color:#d4d4d4;padding:16px;border-radius:8px;margin:8px;overflow:auto;">';
        foreach ($vars as $var) {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
        }
        echo '</pre>';
    }
}

// ─── Miscellaneous ───────────────────────────────────────────────────────────

if (!function_exists('generateId')) {
    /**
     * Generate a unique ID using uniqid.
     *
     * @param string $prefix Prefix for the ID
     * @return string        Unique ID string
     */
    function generateId(string $prefix = 'id_'): string
    {
        return $prefix . uniqid('', true);
    }
}

if (!function_exists('app_name')) {
    /**
     * Get the application name.
     *
     * @return string Application name
     */
    function app_name(): string
    {
        return (string) config('app_name', 'School Management System');
    }
}

if (!function_exists('is_production')) {
    /**
     * Check if the application is running in production.
     *
     * @return bool
     */
    function is_production(): bool
    {
        return !(bool) config('debug', true);
    }
}
