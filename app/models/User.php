<?php
declare(strict_types=1);

final class User extends Model
{
    protected static string $table = 'users';

    public static function find($id): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT u.*, r.slug AS role_slug, r.name AS role_name
             FROM users u LEFT JOIN roles r ON r.id = u.role_id
             WHERE u.id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function findByIdentifier(string $identifier): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT u.*, r.slug AS role_slug, r.name AS role_name
             FROM users u LEFT JOIN roles r ON r.id = u.role_id
             WHERE u.email = ? OR u.username = ? LIMIT 1'
        );
        $stmt->execute([$identifier, $identifier]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function registerFailedAttempt(int $id): void
    {
        $stmt = self::db()->prepare('SELECT failed_login_attempts FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $attempts = (int) $stmt->fetchColumn() + 1;

        if ($attempts >= LOGIN_MAX_ATTEMPTS) {
            $lockUntil = date('Y-m-d H:i:s', time() + LOGIN_LOCKOUT_SECONDS);
            $upd = self::db()->prepare('UPDATE users SET failed_login_attempts = ?, locked_until = ? WHERE id = ?');
            $upd->execute([$attempts, $lockUntil, $id]);
        } else {
            $upd = self::db()->prepare('UPDATE users SET failed_login_attempts = ? WHERE id = ?');
            $upd->execute([$attempts, $id]);
        }
    }

    public static function clearFailedAttempts(int $id): void
    {
        $stmt = self::db()->prepare('UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = ?');
        $stmt->execute([$id]);
    }

    public static function touchLogin(int $id): void
    {
        $stmt = self::db()->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
        $stmt->execute([$id]);
    }
}
