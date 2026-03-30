<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    protected function render(string $view, array $data = [])
    {
        extract($data, EXTR_SKIP);
        include __DIR__ . "/../views/$view.php";
    }

    protected function redirect(string $path)
    {
        header("Location: $path");
        exit();
    }
}
