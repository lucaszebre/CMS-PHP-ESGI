<?php
$error = $error ?? '';
$token = $token ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>

    <?php if ($error !== ''): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/reset-password">
        <?= \App\Services\CsrfService::field() ?>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
        <div>
            <label for="password">New password</label>
            <input id="password" name="password" type="password" placeholder="New password" required minlength="8">
        </div>
        <div>
            <label for="password_confirm">Confirm new password</label>
            <input id="password_confirm" name="password_confirm" type="password" placeholder="Confirm password" required minlength="8">
        </div>
        <div>
            <button type="submit">Reset password</button>
        </div>
    </form>
    <a href="/login">Back to login</a>
</body>
</html>
