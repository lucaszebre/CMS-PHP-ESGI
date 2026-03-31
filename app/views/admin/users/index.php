<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Users</title>
</head>
<body>
    <h1>Users</h1>
    <a href="/">Home</a> |
    <a href="/admin/users/create">New user</a>

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <p>No users yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/admin/users/edit/<?= $user['id'] ?>">Edit</a>
                            <form method="POST" action="/admin/users/delete/<?= $user['id'] ?>" style="display:inline">
                                <?= \App\Services\CsrfService::field() ?>
                                <button type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
