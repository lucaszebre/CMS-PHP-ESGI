<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Edit page</title>
</head>
<body>
    <h1>Edit page</h1>
    <a href="/admin/pages">← Back</a>

    <form method="POST" action="/admin/pages/edit/<?= $page['id'] ?>">
        <div>
            <label>Title<br>
                <input type="text" name="title" value="<?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
        </div>
        <div>
            <label>Slug (URL)<br>
                <input type="text" name="slug" value="<?= htmlspecialchars($page['slug'], ENT_QUOTES, 'UTF-8') ?>" required>
            </label>
        </div>
        <div>
            <label>Content<br>
                <textarea name="content" rows="10" cols="50"><?= htmlspecialchars($page['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </label>
        </div>
        <div>
            <label>Status<br>
                <select name="status">
                    <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                </select>
            </label>
        </div>
        <button type="submit">Save</button>
    </form>
</body>
</html>
