<?php


require_once dirname(path: __DIR__) . '/models/user.php';


class AuthController
{
    private $user;


    public function __construct()
    {
        $this->user = new User();
    }

    public  function login()
    {
        if (empty($_POST)) {
            header('Location: /login');
            exit();
        }

        $password = $_POST['password'] ?? '';
        $email = strtolower(trim($_POST['email'] ?? ''));

        $fetchedUser = $this->user->getUserByEmail($email);

        if ($fetchedUser === null || !password_verify($password, $fetchedUser['password'])) {
            header('Location: /login?error=' . urlencode('Wrong credentials.'));
            exit();
        }

        $_SESSION['username'] = $fetchedUser['username'];

        header('Location: /');
        exit();
    }

    public function logout()
    {


        session_destroy();

        header('Location: /login');
        exit();
    }

    public  function register()
    {
        if (empty($_POST)) {
            header('Location: /register');
            exit();
        }

        $error = array();

        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        $username = ucwords(strtolower(trim($_POST["username"])));
        $email = strtolower(trim($_POST["email"]));

        if ($password !== $password_confirm) {
            $error[] = "password are not the same";
        }

        $fetchedUser = $this->user->getUserByEmail($email);

        if ($fetchedUser) {
            $error[] = "user already present";
        }


        if (!empty($error)) {
            header('Location: /register?error=' . urlencode($error[0]));
            exit();
        }


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->user->addUser($email, $username, $hashedPassword);

        header("Location: /login");


        exit();
        //  check if user already present 
        // super global SESSION 
        // use the user model 
        // add user with password hashed 
    }
}
