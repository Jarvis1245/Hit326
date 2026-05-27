<?php
$editing    = $article !== null;
$page_title = ($editing ? 'Edit Article' : 'New Article') . ' – ' . APP_NAME;
$action     = $editing ? base_url('article/' . $article['id'] . '/edit') : base_url('article/new');
?>

<div class="page-header">
    <h2><?= $editing ? 'Edit Article' : 'New Article' ?></h2>
    <a href="<?= esc(base_url('dashboard')) ?>" class="btn btn-sm">← Back to Dashboard</a>
</div>

<?php if ($editing): ?>
<div class="status-info">
    Status: <span class="status-badge status-<?= esc($article['status']) ?>"><?= esc(ucfirst($article['status'])) ?></span>
</div>
<?php endif; ?>

<form method="POST" action="<?= esc($action) ?>" enctype="multipart/form-data" class="article-form">
    <?= csrf_field() ?>

    <div class="form-group">
        <label for="title">Headline <span class="required">*</span></label>
        <input type="text" id="title" name="title" required maxlength="255"
               value="<?= esc($article['title'] ?? '') ?>">
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="category_id">Category <span class="required">*</span></label>
            <select id="category_id" name="category_id" required>
                <option value="">— Select —</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= esc($cat['id']) ?>"
                    <?= isset($article['category_id']) && $article['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Cover Image <span class="optional">(optional, max 5 MB)</span></label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            <?php if (!empty($article['image_path'])): ?>
            <p class="current-image">
                Current: <img src="<?= esc(base_url($article['image_path'])) ?>" alt="current cover" class="thumb">
                <em>Upload a new file to replace it.</em>
            </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <label for="body">Article Body <span class="required">*</span></label>
        <textarea id="body" name="body" rows="18" required><?= esc($article['body'] ?? '') ?></textarea>
        <p class="form-note">Use blank lines to separate paragraphs.</p>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <?= $editing ? 'Save Changes' : 'Create Draft' ?>
        </button>
        <a href="<?= esc(base_url('dashboard')) ?>" class="btn">Cancel</a>
    </div>
</form>
