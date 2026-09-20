<?php
declare(strict_types=1);

/**
 * Loads .env into getenv()/$_ENV and defines the constants the rest of the
 * app relies on. No Composer dependency — deliberately a tiny parser.
 */

function gidl_load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (strlen($value) >= 2 && $value[0] === '"' && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
        }

        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
    }
}

function env(string $key, $default = null)
{
    $value = $_ENV[$key] ?? getenv($key);
    return $value === false ? $default : $value;
}

gidl_load_env(ROOT_PATH . '/.env');

define('APP_NAME', env('APP_NAME', 'Gita in Daily Life'));
define('APP_ENV', env('APP_ENV', 'local'));
define('APP_DEBUG', filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN));
define('APP_URL', rtrim((string) env('APP_URL', ''), '/'));

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'gita_in_daily_life'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

define('SESSION_NAME', env('SESSION_NAME', 'gidl_session'));
define('DEFAULT_LANGUAGE', env('DEFAULT_LANGUAGE', 'en'));
define('ADMIN_SESSION_LIFETIME', (int) env('ADMIN_SESSION_LIFETIME', 7200));
define('LOGIN_MAX_ATTEMPTS', (int) env('LOGIN_MAX_ATTEMPTS', 5));
define('LOGIN_LOCKOUT_SECONDS', (int) env('LOGIN_LOCKOUT_SECONDS', 300));

define('VIEWS_PATH', ROOT_PATH . '/views');
define('LANGUAGES_PATH', ROOT_PATH . '/languages');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOADS_PATH', ROOT_PATH . '/public/uploads');

/**
 * The base URL path the app is served from, auto-detected from the request so
 * the app is not hardcoded to one folder name or one entry script depth.
 * Every request under /public (frontend or /public/admin/...) resolves to the
 * same base: ".../public".
 */
function gidl_detect_base_path(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $pos = strpos($scriptName, '/public');
    if ($pos !== false) {
        return substr($scriptName, 0, $pos) . '/public';
    }
    return rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
}

define('APP_BASE_PATH', gidl_detect_base_path());

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/php-error.log');
}

date_default_timezone_set('Asia/Dhaka');
