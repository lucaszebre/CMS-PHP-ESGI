<?php

declare(strict_types=1);

class SiteController extends Controller
{
    private AuthSession $authSession;

    public function __construct()
    {
        $this->authSession = new AuthSession();
    }

    public function home(Request $request): void
    {
        $this->render('home', [
            'username' => $this->authSession->username(),
        ]);
    }
}
