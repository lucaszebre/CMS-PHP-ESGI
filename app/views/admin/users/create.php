<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Create user</title>
</head>
<body>
    <h1>Create user</h1>
    <a href="/admin/users">&larr; Back</a>

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/admin/users/create">
        <?= \App\Services\CsrfService::field() ?>
        <div>
            <label>Username<br>
                <input type="text" name="username" required>
            </label>
        </div>
        <div>
            <label>Email<br>
                <input type="email" name="email" required>
            </label>
        </div>
        <div>
            <label>Password<br>
                <input type="password" name="password" required minlength="8">
            </label>
        </div>
        <div>
            <label>Confirm password<br>
                <input type="password" name="password_confirm" required minlength="8">
            </label>
        </div>
        <div>
            <label>Role<br>
                <select name="role">
                    <?php foreach ($roles as $value => $label): ?>
                        <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <button type="submit">Create</button>
    </form>
</body>
</html>
