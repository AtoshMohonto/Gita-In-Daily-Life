<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (Auth::check()) {
    activity_log('logout', 'auth', Auth::id(), 'Admin logged out');
}

Auth::logout();
header('Location: ' . url('admin/login.php'));
exit;
