<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Edit user</title>
</head>
<body>
    <h1>Edit user</h1>
    <a href="/admin/users">&larr; Back</a>

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/admin/users/edit/<?= $user['id'] ?>">
        <?= \App\Services\CsrfService::field() ?>
        <div>
            <label>Username<br>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
        </div>
        <div>
            <label>Email<br>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
        </div>
        <div>
            <label>Role<br>
                <select name="role">
                    <?php foreach ($roles as $value => $label): ?>
                        <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= $user['role'] === $value ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <button type="submit">Save</button>
    </form>
</body>
</html>
