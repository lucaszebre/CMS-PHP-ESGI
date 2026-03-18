<?php

declare(strict_types=1);

class SiteController extends Controller
{
    public function home(): void
    {
        $this->render('home', [
            'username' => $_SESSION['username'] ?? null,
        ]);
    }
}
