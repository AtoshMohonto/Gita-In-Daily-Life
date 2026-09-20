<?php
declare(strict_types=1);

final class Setting extends Model
{
    protected static string $table = 'settings';

    public static function get(string $key, ?string $default = null): ?string
    {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            foreach (self::all() as $row) {
                $cache[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $cache[$key] ?? $default;
    }

    public static function set(string $key, string $value, string $group = 'general'): void
    {
        $existing = self::findBy('setting_key', $key);
        if ($existing) {
            self::update($existing['id'], ['setting_value' => $value, 'setting_group' => $group]);
        } else {
            self::insert(['setting_key' => $key, 'setting_value' => $value, 'setting_group' => $group]);
        }
    }
}
