<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pages</title>
</head>
<body>
    <h1>Pages</h1>
    <a href="/">Home</a>

    <?php if (empty($pages)): ?>
        <p>No pages published yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($pages as $page): ?>
                <li>
                    <a href="/page/<?= htmlspecialchars($page['slug'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                    <small>— <?= htmlspecialchars($page['author'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($page['date'], ENT_QUOTES, 'UTF-8') ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
