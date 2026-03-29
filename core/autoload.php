<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $baseDir = dirname(__DIR__);

    $directories = [
        'App\\Controllers\\' => $baseDir . '/app/controllers/',
        'App\\Models\\'      => $baseDir . '/app/models/',
        'App\\Services\\'    => $baseDir . '/app/services/',
        'App\\Core\\'        => $baseDir . '/core/',
        'App\\Config\\'      => $baseDir . '/config/',
    ];

    foreach ($directories as $directory => $dir) {
        if (!str_starts_with($class, $directory)) {
            continue;
        }

        $className = substr($class, strlen($directory));
        $filePath = $dir . $className . '.php';

        if (is_file($filePath)) {
            require_once $filePath;
            return;
        }
    }
});
