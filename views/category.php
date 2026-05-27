<?php $page_title = esc($cat['name']) . ' – ' . APP_NAME; ?>

<div class="page-header">
    <h2><?= esc($cat['name']) ?></h2>
</div>

<?php if (empty($articles)): ?>
    <p class="empty-state">No published articles in this category yet.</p>
<?php else: ?>
<div class="grid-3">
    <?php foreach ($articles as $art): ?>
    <article class="card">
        <?php if ($art['image_path']): ?>
        <a href="<?= esc(base_url('article/' . $art['id'])) ?>">
            <img src="<?= esc(base_url($art['image_path'])) ?>" alt="<?= esc($art['title']) ?>" class="card-img">
        </a>
        <?php else: ?>
        <div class="card-img placeholder-img"></div>
        <?php endif; ?>
        <div class="card-body">
            <h4><a href="<?= esc(base_url('article/' . $art['id'])) ?>"><?= esc($art['title']) ?></a></h4>
            <p class="article-meta"><?= esc($art['author_name']) ?> &middot; <?= esc(time_ago($art['published_at'])) ?></p>
            <p><?= esc(mb_substr(strip_tags($art['body']), 0, 140)) ?>…</p>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>
