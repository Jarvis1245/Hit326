<?php $page_title = 'Moderate Comments – ' . APP_NAME; ?>

<div class="page-header">
    <h2>Pending Comments</h2>
    <a href="<?= esc(base_url('editor/queue')) ?>" class="btn btn-sm">← Article Queue</a>
</div>

<?php if (empty($comments)): ?>
    <p class="empty-state">No comments awaiting moderation.</p>
<?php else: ?>
<table class="data-table">
    <thead>
        <tr>
            <th>Article</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Submitted</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($comments as $cmt): ?>
        <tr>
            <td><a href="<?= esc(base_url('article/' . $cmt['article_id'])) ?>"><?= esc($cmt['article_title']) ?></a></td>
            <td><?= esc($cmt['author_name']) ?></td>
            <td><?= esc(mb_substr($cmt['body'], 0, 120)) ?><?= mb_strlen($cmt['body']) > 120 ? '…' : '' ?></td>
            <td><?= esc(time_ago($cmt['created_at'])) ?></td>
            <td class="actions">
                <form method="POST" action="<?= esc(base_url('editor/comment/' . $cmt['id'] . '/approve')) ?>" class="inline-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-primary">Approve</button>
                </form>
                <form method="POST" action="<?= esc(base_url('editor/comment/' . $cmt['id'] . '/delete')) ?>" class="inline-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this comment permanently?')">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
