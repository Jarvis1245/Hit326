<?php $page_title = '404 Not Found – ' . APP_NAME; ?>

<div class="error-page">
    <h2>404 &mdash; Page Not Found</h2>
    <p><?= esc($message ?? 'The page you requested could not be found.') ?></p>
    <a href="<?= esc(base_url('')) ?>" class="btn btn-primary">← Back to Home</a>
</div>
