<?php
$error = $error ?? '';
$success = $success ?? '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <p>Please sign in to continue.</p>

    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="/login" autocomplete="on">
        <?= \App\Services\CsrfService::field() ?>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="you@example.com" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Your password" required>
        </div>

        <div>
            <button type="submit">Log in</button>
        </div>
    </form>
    <a href="/register">Go to register page</a>

</body>

</html>