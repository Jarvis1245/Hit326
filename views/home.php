<?php $page_title = APP_NAME . ' – Home'; ?>

<?php if (empty($articles)): ?>
    <p class="empty-state">No articles published yet. Check back soon.</p>
<?php else: ?>

<?php $featured = array_shift($articles); ?>
<section class="featured-article">
    <a href="<?= esc(base_url('article/' . $featured['id'])) ?>">
        <?php if ($featured['image_path']): ?>
        <img src="<?= esc(base_url($featured['image_path'])) ?>" alt="<?= esc($featured['title']) ?>" class="featured-img">
        <?php else: ?>
        <div class="featured-img placeholder-img"></div>
        <?php endif; ?>
        <div class="featured-body">
            <span class="category-badge"><?= esc($featured['category_name']) ?></span>
            <h2><?= esc($featured['title']) ?></h2>
            <p class="article-meta">By <?= esc($featured['author_name']) ?> &middot; <?= esc(time_ago($featured['published_at'])) ?></p>
            <p class="article-excerpt"><?= esc(mb_substr(strip_tags($featured['body']), 0, 220)) ?>…</p>
        </div>
    </a>
</section>

<?php if (!empty($articles)): ?>
<section class="article-grid">
    <h3 class="section-heading">Latest Stories</h3>
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
                <span class="category-badge small"><?= esc($art['category_name']) ?></span>
                <h4><a href="<?= esc(base_url('article/' . $art['id'])) ?>"><?= esc($art['title']) ?></a></h4>
                <p class="article-meta"><?= esc($art['author_name']) ?> &middot; <?= esc(time_ago($art['published_at'])) ?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php endif; ?>
