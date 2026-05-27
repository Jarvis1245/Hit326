<?php $page_title = esc($article['title']) . ' – ' . APP_NAME; ?>

<article class="article-full">
    <header class="article-header">
        <span class="category-badge">
            <a href="<?= esc(base_url('category/' . $article['category_slug'])) ?>"><?= esc($article['category_name']) ?></a>
        </span>
        <h1><?= esc($article['title']) ?></h1>
        <p class="article-meta">
            By <strong><?= esc($article['author_name']) ?></strong>
            &middot; <?= esc(format_date($article['published_at'])) ?>
            &middot; <?= esc(time_ago($article['published_at'])) ?>
        </p>
    </header>

    <?php if ($article['image_path']): ?>
    <figure class="article-figure">
        <img src="<?= esc(base_url($article['image_path'])) ?>" alt="<?= esc($article['title']) ?>">
    </figure>
    <?php endif; ?>

    <div class="article-body">
        <?php foreach (explode("\n", $article['body']) as $para): ?>
            <?php $para = trim($para); if ($para !== ''): ?>
            <p><?= esc($para) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</article>

<section class="comments-section">
    <h3>Comments (<?= count($comments) ?>)</h3>

    <?php if (empty($comments)): ?>
        <p class="empty-state">No comments yet. Be the first!</p>
    <?php else: ?>
        <?php foreach ($comments as $cmt): ?>
        <div class="comment">
            <strong><?= esc($cmt['author_name']) ?></strong>
            <span class="comment-time"><?= esc(time_ago($cmt['created_at'])) ?></span>
            <p><?= esc($cmt['body']) ?></p>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="comment-form">
        <h4>Leave a Comment</h4>
        <form method="POST" action="<?= esc(base_url('article/' . $article['id'] . '/comment')) ?>">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="author_name">Your Name</label>
                <input type="text" id="author_name" name="author_name" required maxlength="100">
            </div>
            <div class="form-group">
                <label for="body">Comment</label>
                <textarea id="body" name="body" rows="4" required maxlength="2000"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Comment</button>
            <p class="form-note">Comments are moderated before appearing.</p>
        </form>
    </div>
</section>
