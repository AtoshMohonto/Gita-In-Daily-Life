<?php
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole(['super_admin', 'admin', 'editor']);

$config = require __DIR__ . '/config.php';
(new AdminModuleController($config))->handle();
