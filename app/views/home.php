<?php
$username = $username ?? null;
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <h1>Home page</h1>

    <?php if ($username !== null): ?>
        <p>You are logged in as <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>.</p>
        <p>
            <a href="/logout">Logout</a>
        </p>
    <?php else: ?>
        <p>You are not logged in.</p>
        <p>
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        </p>
    <?php endif; ?>
</body>

</html>
