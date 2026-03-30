<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Pages</title>
</head>
<body>
    <h1>Pages</h1>
    <a href="/">Home</a> |
    <a href="/admin/pages/create">New page</a>

    <?php if (empty($pages)): ?>
        <p>No pages yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($page['slug'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($page['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($page['author'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($page['date'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="/admin/pages/edit/<?= $page['id'] ?>">Edit</a>
                            <form method="POST" action="/admin/pages/delete/<?= $page['id'] ?>" style="display:inline">
                                <button type="submit" onclick="return confirm('Delete this page?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
