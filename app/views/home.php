<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h1>Home page</h1>

    <nav>
        <a href="/pages">Pages</a>
        <?php if (in_array($role ?? null, ['admin', 'editor'], true)): ?>
            | <a href="/admin/pages">Admin</a>
        <?php endif; ?>
    </nav>

    <?php if ($username !== null): ?>
        <p>You are logged in as <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>.</p>
        <a href="/logout">Logout</a>
    <?php else: ?>
        <p>You are not logged in.</p>
        <a href="/login">Login</a> |
        <a href="/register">Register</a>
    <?php endif; ?>
</body>
</html>
