<?php
$error = $error ?? '';
$success = $success ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Forgot Password</h1>
    <p>Enter your email address and we will send you a link to reset your password.</p>

    <?php if ($error !== ''): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p style="color:green"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/forgot-password">
        <?= \App\Services\CsrfService::field() ?>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="you@example.com" required>
        </div>
        <div>
            <button type="submit">Send reset link</button>
        </div>
    </form>
    <a href="/login">Back to login</a>
</body>
</html>
