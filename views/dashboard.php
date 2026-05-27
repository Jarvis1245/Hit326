<?php $page_title = 'My Articles – ' . APP_NAME; ?>

<div class="page-header">
    <h2>My Articles</h2>
    <a href="<?= esc(base_url('article/new')) ?>" class="btn btn-primary">+ New Article</a>
</div>

<?php if (empty($articles)): ?>
    <p class="empty-state">You haven't written any articles yet. <a href="<?= esc(base_url('article/new')) ?>">Create your first one.</a></p>
<?php else: ?>
<table class="data-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $art): ?>
        <tr>
            <td>
                <?php if ($art['status'] === 'published'): ?>
                    <a href="<?= esc(base_url('article/' . $art['id'])) ?>"><?= esc($art['title']) ?></a>
                <?php else: ?>
                    <?= esc($art['title']) ?>
                <?php endif; ?>
            </td>
            <td><?= esc($art['category_name']) ?></td>
            <td><span class="status-badge status-<?= esc($art['status']) ?>"><?= esc(ucfirst($art['status'])) ?></span></td>
            <td><?= esc(time_ago($art['updated_at'])) ?></td>
            <td class="actions">
                <?php if ($art['status'] === 'draft'): ?>
                    <a href="<?= esc(base_url('article/' . $art['id'] . '/edit')) ?>" class="btn btn-sm">Edit</a>
                    <form method="POST" action="<?= esc(base_url('article/' . $art['id'] . '/submit')) ?>" class="inline-form">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm btn-accent"
                            onclick="return confirm('Submit this article for review?')">Submit for Review</button>
                    </form>
                <?php elseif ($art['status'] === 'pending'): ?>
                    <span class="muted">Awaiting editor review</span>
                <?php else: ?>
                    <a href="<?= esc(base_url('article/' . $art['id'])) ?>" class="btn btn-sm">View</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
