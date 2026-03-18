<?php
$error = $error ?? '';
$success = $success ?? '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <h1>Register</h1>
    <p>Create a new account.</p>

    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="/register" autocomplete="on">
        <div>
            <label for="username">Username</label>
            <input id="username" name="username" type="text" placeholder="Your username" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="you@example.com" required>
        </div>


        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Your password" required>
        </div>

        <div>
            <label for="password_confirm">Confirm password</label>
            <input id="password_confirm" name="password_confirm" type="password" placeholder="Repeat your password" required>
        </div>

        <div>
            <button type="submit">Register</button>
        </div>
    </form>
    <a href="/login">Go to Login Page</a>

</body>

</html>