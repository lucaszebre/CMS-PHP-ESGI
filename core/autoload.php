<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $baseDir = dirname(__DIR__);
    $directories = [
        $baseDir . '/app/controllers',
        $baseDir . '/app/models',
        $baseDir . '/app/services',
        $baseDir . '/core',
        $baseDir . '/config',
    ];

    foreach ($directories as $directory) {
        $filePath = $directory . '/' . $class . '.php';

        if (is_file($filePath)) {
            require_once $filePath;
            return;
        }
    }
});
