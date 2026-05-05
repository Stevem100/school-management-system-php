<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | The name of the application displayed throughout the system.
    |
    */
    'app_name' => 'School Management System',

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | The base URL of the application. Leave empty to auto-detect.
    |
    */
    'app_url' => '',

    /*
    |--------------------------------------------------------------------------
    | MySQL Database Configuration
    |--------------------------------------------------------------------------
    |
    | Credentials for connecting to the MySQL database via PDO.
    | All values can be overridden via environment variables.
    |
    */
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_port' => getenv('DB_PORT') ?: '3306',
    'db_name' => getenv('DB_NAME') ?: 'school_erp',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_password' => getenv('DB_PASSWORD') ?: '',

    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    |
    | The default timezone for the application. Used by all date/time functions.
    |
    */
    'timezone' => 'Africa/Nairobi',

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    |
    | Controls session lifetime in seconds and the session cookie name.
    |
    */
    'session_lifetime' => 86400, // 24 hours
    'session_name' => 'school_erp_session',

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    |
    | Password salt used for hashing passwords.
    |
    */
    'password_salt' => '_school_erp_salt',

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Enable debug mode to see detailed error messages. Set to false in production.
    |
    */
    'debug' => true,
];
