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

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/admin/pages/edit/<?= $page['id'] ?>">
        <?= \App\Services\CsrfService::field() ?>
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
                <textarea name="content" rows="10" cols="50" maxlength="<?= \App\Models\Page::MAX_CONTENT_LENGTH ?>"><?= htmlspecialchars($page['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </label>
        </div>
        <div>
            <label>Status<br>
                <select name="status">
                    <?php foreach ($statuses as $value => $label): ?>
                        <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= $page['status'] === $value ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <button type="submit">Save</button>
    </form>
</body>
</html>
