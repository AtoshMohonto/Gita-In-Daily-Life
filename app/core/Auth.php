<?php
declare(strict_types=1);

final class Auth
{
    private static ?array $userCache = null;

    public static function attempt(string $identifier, string $password): array
    {
        $user = User::findByIdentifier($identifier);

        if ($user === null) {
            return ['success' => false, 'error' => 'Invalid credentials.'];
        }

        if (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
            return ['success' => false, 'error' => 'Account temporarily locked due to repeated failed logins. Try again later.'];
        }

        if (isset($user['status']) && $user['status'] !== 'active') {
            return ['success' => false, 'error' => 'This account is not active.'];
        }

        if (!password_verify($password, $user['password_hash'])) {
            User::registerFailedAttempt((int) $user['id']);
            return ['success' => false, 'error' => 'Invalid credentials.'];
        }

        User::clearFailedAttempts((int) $user['id']);
        User::touchLogin((int) $user['id']);
        self::login($user);

        return ['success' => true];
    }

    public static function login(array $user): void
    {
        Session::regenerate();
        Session::set('admin_user_id', (int) $user['id']);
        Session::set('admin_user_role', $user['role_slug'] ?? null);
        self::$userCache = $user;
    }

    public static function logout(): void
    {
        Session::remove('admin_user_id');
        Session::remove('admin_user_role');
        self::$userCache = null;
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::has('admin_user_id');
    }

    public static function id(): ?int
    {
        $id = Session::get('admin_user_id');
        return $id === null ? null : (int) $id;
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        if (self::$userCache === null) {
            self::$userCache = User::find(self::id());
        }
        return self::$userCache;
    }

    public static function role(): ?string
    {
        return Session::get('admin_user_role');
    }

    public static function hasRole(array $roles): bool
    {
        $role = self::role();
        return $role !== null && in_array($role, $roles, true);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: ' . url('admin/login.php'));
            exit;
        }
    }

    public static function requireRole(array $roles): void
    {
        self::requireLogin();
        if (!self::hasRole($roles)) {
            http_response_code(403);
            echo '<h1>403 Forbidden</h1><p>You do not have permission to access this page.</p>';
            exit;
        }
    }
}
