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
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Credentials for connecting to the Supabase PostgREST API.
    | The secret key is used as the service_role key, which bypasses RLS.
    |
    */
    'supabase_url' => getenv('SUPABASE_URL') ?: '',
    'supabase_publishable_key' => getenv('SUPABASE_PUBLISHABLE_KEY') ?: '',
    'supabase_secret_key' => getenv('SUPABASE_SECRET_KEY') ?: '',

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
