<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

require_once APP_PATH . '/config/config.php';

spl_autoload_register(function (string $class): void {
    static $dirs = null;
    if ($dirs === null) {
        $dirs = [
            APP_PATH . '/core',
            APP_PATH . '/models',
            APP_PATH . '/services',
            ROOT_PATH . '/modules',
        ];
    }
    foreach ($dirs as $dir) {
        $file = $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

require_once APP_PATH . '/helpers/functions.php';

Session::start();
