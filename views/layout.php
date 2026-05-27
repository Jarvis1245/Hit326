<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($page_title ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= esc(base_url('css/style.css')) ?>">
    <link rel="alternate" type="application/rss+xml" title="<?= esc(APP_NAME) ?> RSS" href="<?= esc(base_url('feed.xml')) ?>">
</head>
<body>
<header class="site-header">
    <div class="container">
        <div class="header-top">
            <div class="header-date"><?= date('l, j F Y') ?></div>
            <div class="header-auth">
                <?php $u = current_user(); if ($u): ?>
                    <span><?= esc($u['name']) ?> (<?= esc($u['role']) ?>)</span>
                    <?php if ($u['role'] === 'editor'): ?>
                        <a href="<?= esc(base_url('editor/queue')) ?>">Queue</a>
                        <a href="<?= esc(base_url('editor/comments')) ?>">Comments</a>
                    <?php else: ?>
                        <a href="<?= esc(base_url('dashboard')) ?>">My Articles</a>
                    <?php endif; ?>
                    <a href="<?= esc(base_url('logout')) ?>">Log out</a>
                <?php else: ?>
                    <a href="<?= esc(base_url('login')) ?>">Log in</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="masthead">
            <a href="<?= esc(base_url('')) ?>" class="masthead-link">
                <h1><?= esc(APP_NAME) ?></h1>
                <p class="masthead-tagline">Your window to Australia &amp; Asia</p>
            </a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="<?= esc(base_url('')) ?>">Home</a></li>
                <?php foreach (get_all_categories() as $cat): ?>
                <li><a href="<?= esc(base_url('category/' . $cat['slug'])) ?>"><?= esc($cat['name']) ?></a></li>
                <?php endforeach; ?>
                <li><a href="<?= esc(base_url('feed.xml')) ?>">RSS</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="container main-content">
    <?php $err = flash_get('error'); if ($err): ?>
        <div class="alert alert-error"><?= esc($err) ?></div>
    <?php endif; ?>
    <?php $ok = flash_get('success'); if ($ok): ?>
        <div class="alert alert-success"><?= esc($ok) ?></div>
    <?php endif; ?>

    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> <?= esc(APP_NAME) ?> &mdash; HIT326 Group Project &middot; Charles Darwin University</p>
        <p>Team: Subodh Gautam &middot; Sagar Khanal &middot; Mahmud Didar</p>
    </div>
</footer>
</body>
</html>
