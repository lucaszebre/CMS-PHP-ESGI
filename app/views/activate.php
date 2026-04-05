<?php
$error = $error ?? '';
$success = $success ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activated</title>
</head>
<body>
    <h1>Account Activation</h1>

    <?php if ($error !== ''): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <p style="color:green"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <p><a href="/login">Go to login</a></p>
</body>
</html>
