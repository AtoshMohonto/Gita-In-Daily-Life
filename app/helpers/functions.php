<?php
declare(strict_types=1);

/** Escape for safe HTML output. */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Build an absolute app URL relative to the detected base path. */
function url(string $path = ''): string
{
    return APP_BASE_PATH . '/' . ltrim($path, '/');
}

/** Build a URL for a static asset under public/assets. */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/** Turn arbitrary text into a URL-safe slug. */
function slugify(string $text): string
{
    $text = trim($text);
    $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if ($transliterated !== false && $transliterated !== '') {
        $text = $transliterated;
    }
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    $text = trim($text, '-');
    return $text === '' ? 'item-' . substr(bin2hex(random_bytes(4)), 0, 8) : $text;
}

/** Get/set a one-time flash message. */
function flash(string $key, ?string $message = null)
{
    return Session::flash($key, $message);
}

/** Repopulate a form field from the last failed submission. */
function old(string $key, string $default = ''): string
{
    $old = Session::get('_old', []);
    return e($old[$key] ?? $default);
}

function set_old(array $data): void
{
    Session::set('_old', $data);
}

function clear_old(): void
{
    Session::remove('_old');
}

/** Current UI language code (en|bn). */
function current_lang(): string
{
    return Session::get('lang', DEFAULT_LANGUAGE);
}

/** Translate a UI string key using /languages/{lang}.php. */
function __t(string $key, array $replace = []): string
{
    static $cache = [];
    $lang = current_lang();

    if (!isset($cache[$lang])) {
        $file = LANGUAGES_PATH . '/' . $lang . '.php';
        if (!is_file($file)) {
            $file = LANGUAGES_PATH . '/' . DEFAULT_LANGUAGE . '.php';
        }
        $cache[$lang] = is_file($file) ? require $file : [];
    }

    $text = $cache[$lang][$key] ?? $key;
    foreach ($replace as $k => $v) {
        $text = str_replace(':' . $k, (string) $v, $text);
    }
    return $text;
}

/** Record an admin action in activity_logs (best-effort, never fatal). */
function activity_log(string $action, string $module, ?int $recordId = null, string $description = ''): void
{
    try {
        ActivityLog::record($action, $module, $recordId, $description);
    } catch (Throwable $e) {
        error_log('activity_log failed: ' . $e->getMessage());
    }
}

/** Simple content-status badge label. */
function status_label(string $status): string
{
    return match ($status) {
        'draft' => 'Draft',
        'pending' => 'Pending Review',
        'published' => 'Published',
        'archived' => 'Archived',
        default => ucfirst($status),
    };
}

function status_badge_class(string $status): string
{
    return match ($status) {
        'draft' => 'secondary',
        'pending' => 'warning',
        'published' => 'success',
        'archived' => 'dark',
        default => 'light',
    };
}

/** Truncate plain text to a length, on a word boundary. */
function str_excerpt(?string $text, int $length = 160): string
{
    $text = trim((string) $text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    $truncated = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = mb_substr($truncated, 0, $lastSpace);
    }
    return $truncated . '…';
}
