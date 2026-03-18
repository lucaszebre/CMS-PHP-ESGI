<?php


class SiteController
{
    protected function render($view, $data = [])
    {
        extract($data);

        include __DIR__ . "/../views/$view.php";
    }

    public function home()
    {
        $this->render('home', [
            'username' => $_SESSION['username'] ?? null,
        ]);
    }
    public function about()
    {
        echo "About page!";
    }

    public  function login()
    {
        $this->render('login', [
            'error' => isset($_GET['error']) ? trim((string) $_GET['error']) : '',
            'success' => isset($_GET['success']) ? trim((string) $_GET['success']) : '',
        ]);
    }

    public  function register()
    {
        $this->render('register', [
            'error' => isset($_GET['error']) ? trim((string) $_GET['error']) : '',
            'success' => isset($_GET['success']) ? trim((string) $_GET['success']) : '',
        ]);
    }
}
