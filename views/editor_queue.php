<?php $page_title = 'Editor Queue – ' . APP_NAME; ?>

<div class="page-header">
    <h2>Pending Articles</h2>
    <a href="<?= esc(base_url('editor/comments')) ?>" class="btn btn-sm">Moderate Comments →</a>
</div>

<?php if (empty($articles)): ?>
    <p class="empty-state">No articles awaiting review.</p>
<?php else: ?>
<div class="queue-list">
    <?php foreach ($articles as $art): ?>
    <div class="queue-item">
        <div class="queue-meta">
            <span class="category-badge small"><?= esc($art['category_name']) ?></span>
            <span class="muted">by <?= esc($art['author_name']) ?> &middot; submitted <?= esc(time_ago($art['updated_at'])) ?></span>
        </div>
        <h3><?= esc($art['title']) ?></h3>
        <div class="queue-preview">
            <?= esc(mb_substr(strip_tags($art['body']), 0, 300)) ?>…
        </div>
        <div class="queue-actions">
            <form method="POST" action="<?= esc(base_url('editor/' . $art['id'] . '/approve')) ?>" class="inline-form">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Approve and publish this article?')">Approve &amp; Publish</button>
            </form>
            <form method="POST" action="<?= esc(base_url('editor/' . $art['id'] . '/reject')) ?>" class="inline-form">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Return this article to draft?')">Reject (Return to Draft)</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
