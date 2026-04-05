<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
    <a href="/pages">← Back to pages</a>

    <h1><?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <small>By <?= htmlspecialchars($page['author'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($page['date'], ENT_QUOTES, 'UTF-8') ?></small>

    <p><?= nl2br(htmlspecialchars($page['content'], ENT_QUOTES, 'UTF-8')) ?></p>
</body>
</html>
