<?php

declare(strict_types=1);

namespace Core;

/**
 * Session Management
 *
 * Provides a clean interface for PHP session operations including
 * standard session values and flash data that is available only
 * on the next request.
 */
class Session
{
    /** @var string Key prefix for flash data stored in the session */
    private const FLASH_PREFIX = 'flash_';

    /** @var string Key used to store old flash data keys (for removal) */
    private const FLASH_OLD_KEY = '_flash_old';

    /** @var bool Whether the session has been started */
    private static bool $started = false;

    /**
     * Start the session if not already started.
     *
     * Configures session from app config: name, lifetime, cookie settings.
     *
     * @return void
     */
    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $sessionName = config('session_name', 'school_erp_session');
        $lifetime = (int) config('session_lifetime', 86400);

        session_name($sessionName);

        ini_set('session.gc_maxlifetime', (string) $lifetime);
        ini_set('session.cookie_lifetime', (string) $lifetime);
        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_strict_mode', '1');

        session_start();

        self::$started = true;

        // Re-flash: move new flash data to old, mark new for removal next request
        self::ageFlashData();
    }

    /**
     * Set a session value.
     *
     * @param string $key   Session key
     * @param mixed  $value Value to store
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value.
     *
     * @param string $key     Session key
     * @param mixed  $default Default value if key doesn't exist
     * @return mixed          Session value or default
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session key exists.
     *
     * @param string $key Session key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session key.
     *
     * @param string $key Session key to remove
     * @return void
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get and remove a session value (pull).
     *
     * @param string $key     Session key
     * @param mixed  $default Default value
     * @return mixed          Session value or default
     */
    public static function pull(string $key, $default = null)
    {
        $value = self::get($key, $default);
        self::remove($key);
        return $value;
    }

    /**
     * Destroy the entire session.
     *
     * Clears all session data and destroys the session cookie.
     *
     * @return void
     */
    public static function destroy(): void
    {
        // Clear all session variables
        $_SESSION = [];

        // Delete session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Destroy the session
        session_destroy();
        self::$started = false;
    }

    /**
     * Regenerate the session ID to prevent session fixation attacks.
     *
     * @param bool $destroy Whether to destroy the old session data
     * @return void
     */
    public static function regenerate(bool $destroy = false): void
    {
        session_regenerate_id($destroy);
    }

    /**
     * Flash a value to the session (available only on the next request).
     *
     * @param string $key   Flash key
     * @param mixed  $value Value to flash
     * @return void
     */
    public static function flash(string $key, $value): void
    {
        $_SESSION[self::FLASH_PREFIX . $key] = $value;
        // Note: we do NOT mark this as old here.
        // ageFlashData() will mark it as old on the NEXT request,
        // so it persists for exactly one subsequent read.
    }

    /**
     * Get a flash value (reads and keeps it for the next request).
     *
     * @param string $key     Flash key
     * @param mixed  $default Default value
     * @return mixed          Flash value or default
     */
    public static function getFlash(string $key, $default = null)
    {
        $flashKey = self::FLASH_PREFIX . $key;

        if (isset($_SESSION[$flashKey])) {
            return $_SESSION[$flashKey];
        }

        return $default;
    }

    /**
     * Get and immediately remove a flash value.
     *
     * @param string $key     Flash key
     * @param mixed  $default Default value
     * @return mixed          Flash value or default
     */
    public static function pullFlash(string $key, $default = null)
    {
        $value = self::getFlash($key, $default);
        self::removeFlash($key);
        return $value;
    }

    /**
     * Remove a flash value.
     *
     * @param string $key Flash key
     * @return void
     */
    public static function removeFlash(string $key): void
    {
        unset($_SESSION[self::FLASH_PREFIX . $key]);
    }

    /**
     * Check if a flash key exists.
     *
     * @param string $key Flash key
     * @return bool
     */
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION[self::FLASH_PREFIX . $key]);
    }

    /**
     * Get all session data.
     *
     * @return array
     */
    public static function all(): array
    {
        return $_SESSION;
    }

    /**
     * Get the session ID.
     *
     * @return string
     */
    public static function id(): string
    {
        return session_id();
    }

    /**
     * Age the flash data: remove old flashes and re-key new flashes.
     *
     * Called automatically when the session is started.
     *
     * @return void
     */
    private static function ageFlashData(): void
    {
        // Step 1: Remove flashes that were marked as old in the previous request
        $old = $_SESSION[self::FLASH_OLD_KEY] ?? [];
        foreach ($old as $key => $_) {
            unset($_SESSION[self::FLASH_PREFIX . $key]);
        }

        // Step 2: Mark all CURRENT flash keys as old
        // (they will be removed on the NEXT request, but are still available NOW)
        $_SESSION[self::FLASH_OLD_KEY] = [];
        foreach ($_SESSION as $key => $value) {
            if (str_starts_with((string) $key, self::FLASH_PREFIX)) {
                $flashKey = substr($key, strlen(self::FLASH_PREFIX));
                $_SESSION[self::FLASH_OLD_KEY][$flashKey] = true;
            }
        }
    }

    /**
     * Check if the session has been started.
     *
     * @return bool
     */
    public static function isStarted(): bool
    {
        return self::$started || session_status() === PHP_SESSION_ACTIVE;
    }
}
