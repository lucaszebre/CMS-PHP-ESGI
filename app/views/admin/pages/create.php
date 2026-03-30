<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Create page</title>
</head>
<body>
    <h1>Create page</h1>
    <a href="/admin/pages">← Back</a>

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/admin/pages/create">
        <div>
            <label>Title<br>
                <input type="text" name="title" required>
            </label>
        </div>
        <div>
            <label>Slug (URL)<br>
                <input type="text" name="slug" required>
            </label>
        </div>
        <div>
            <label>Content<br>
                <textarea name="content" rows="10" cols="50"></textarea>
            </label>
        </div>
        <div>
            <label>Status<br>
                <select name="status">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </label>
        </div>
        <button type="submit">Create</button>
    </form>
</body>
</html>
