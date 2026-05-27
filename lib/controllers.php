<?php

// ══════════════════════════════════════════════════════════════════════════════
// DATABASE FUNCTIONS
// ══════════════════════════════════════════════════════════════════════════════

function get_all_categories(): array
{
    return db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
}

function get_category_by_slug(string $slug): ?array
{
    $st = db()->prepare('SELECT * FROM categories WHERE slug = ?');
    $st->execute([$slug]);
    return $st->fetch() ?: null;
}

function get_published_articles(int $limit = 10, int $offset = 0): array
{
    $st = db()->prepare(
        'SELECT a.*, u.name AS author_name, c.name AS category_name, c.slug AS category_slug
         FROM articles a
         JOIN users      u ON u.id = a.author_id
         JOIN categories c ON c.id = a.category_id
         WHERE a.status = \'published\'
         ORDER BY a.published_at DESC
         LIMIT ? OFFSET ?'
    );
    $st->bindValue(1, $limit,  PDO::PARAM_INT);
    $st->bindValue(2, $offset, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
}

function get_articles_by_category(int $categoryId, int $limit = 10, int $offset = 0): array
{
    $st = db()->prepare(
        'SELECT a.*, u.name AS author_name, c.name AS category_name, c.slug AS category_slug
         FROM articles a
         JOIN users      u ON u.id = a.author_id
         JOIN categories c ON c.id = a.category_id
         WHERE a.status = \'published\' AND a.category_id = ?
         ORDER BY a.published_at DESC
         LIMIT ? OFFSET ?'
    );
    $st->bindValue(1, $categoryId, PDO::PARAM_INT);
    $st->bindValue(2, $limit,      PDO::PARAM_INT);
    $st->bindValue(3, $offset,     PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
}

function get_article_by_id(int $id): ?array
{
    $st = db()->prepare(
        'SELECT a.*, u.name AS author_name, c.name AS category_name, c.slug AS category_slug
         FROM articles a
         JOIN users      u ON u.id = a.author_id
         JOIN categories c ON c.id = a.category_id
         WHERE a.id = ?'
    );
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->execute();
    return $st->fetch() ?: null;
}

function get_articles_by_author(int $authorId): array
{
    $st = db()->prepare(
        'SELECT a.*, c.name AS category_name, c.slug AS category_slug
         FROM articles a
         JOIN categories c ON c.id = a.category_id
         WHERE a.author_id = ?
         ORDER BY a.updated_at DESC'
    );
    $st->bindValue(1, $authorId, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
}

function get_pending_articles(): array
{
    return db()->query(
        'SELECT a.*, u.name AS author_name, c.name AS category_name
         FROM articles a
         JOIN users      u ON u.id = a.author_id
         JOIN categories c ON c.id = a.category_id
         WHERE a.status = \'pending\'
         ORDER BY a.updated_at ASC'
    )->fetchAll();
}

function create_article(array $data): int
{
    $now = date('Y-m-d H:i:s');
    $st = db()->prepare(
        'INSERT INTO articles (title, slug, body, image_path, status, author_id, category_id, created_at, updated_at)
         VALUES (?, ?, ?, ?, \'draft\', ?, ?, ?, ?)'
    );
    $st->execute([
        $data['title'],
        $data['slug'],
        $data['body'],
        $data['image_path'] ?? null,
        $data['author_id'],
        $data['category_id'],
        $now,
        $now,
    ]);
    return (int) db()->lastInsertId();
}

function update_article(int $id, array $data): void
{
    $now = date('Y-m-d H:i:s');
    $st = db()->prepare(
        'UPDATE articles
         SET title = ?, slug = ?, body = ?, image_path = ?, category_id = ?, updated_at = ?
         WHERE id = ?'
    );
    $st->execute([
        $data['title'],
        $data['slug'],
        $data['body'],
        $data['image_path'] ?? null,
        $data['category_id'],
        $now,
        $id,
    ]);
}

function submit_article(int $id, int $authorId): void
{
    $now = date('Y-m-d H:i:s');
    $st = db()->prepare(
        "UPDATE articles SET status = 'pending', updated_at = ? WHERE id = ? AND author_id = ? AND status = 'draft'"
    );
    $st->execute([$now, $id, $authorId]);
}

function approve_article(int $id): void
{
    $now = date('Y-m-d H:i:s');
    $st = db()->prepare(
        "UPDATE articles SET status = 'published', published_at = ?, updated_at = ? WHERE id = ? AND status = 'pending'"
    );
    $st->execute([$now, $now, $id]);
}

function reject_article(int $id): void
{
    $now = date('Y-m-d H:i:s');
    $st = db()->prepare(
        "UPDATE articles SET status = 'draft', updated_at = ? WHERE id = ? AND status = 'pending'"
    );
    $st->execute([$now, $id]);
}

// ── Comments ──────────────────────────────────────────────────────────────────

function get_approved_comments(int $articleId): array
{
    $st = db()->prepare(
        "SELECT * FROM comments WHERE article_id = ? AND status = 'approved' ORDER BY created_at ASC"
    );
    $st->bindValue(1, $articleId, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
}

function get_pending_comments(): array
{
    return db()->query(
        "SELECT cmt.*, a.title AS article_title, a.id AS article_id
         FROM comments cmt
         JOIN articles a ON a.id = cmt.article_id
         WHERE cmt.status = 'pending'
         ORDER BY cmt.created_at ASC"
    )->fetchAll();
}

function create_comment(int $articleId, string $authorName, string $body): void
{
    $now = date('Y-m-d H:i:s');
    $st = db()->prepare(
        "INSERT INTO comments (article_id, author_name, body, status, created_at)
         VALUES (?, ?, ?, 'pending', ?)"
    );
    $st->execute([$articleId, $authorName, $body, $now]);
}

function approve_comment(int $id): void
{
    $st = db()->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->execute();
}

function delete_comment(int $id): void
{
    $st = db()->prepare('DELETE FROM comments WHERE id = ?');
    $st->bindValue(1, $id, PDO::PARAM_INT);
    $st->execute();
}

// ── Image upload ──────────────────────────────────────────────────────────────

// Returns the public-relative path (e.g. "uploads/abc123.jpg") or null.
function handle_image_upload(string $field): ?string
{
    if (empty($_FILES[$field]['tmp_name'])) return null;

    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > 5 * 1024 * 1024) {
        flash_set('error', 'Image must be 5 MB or smaller.');
        return null;
    }

    $mime = mime_content_type($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        flash_set('error', 'Only JPEG, PNG, GIF, and WebP images are accepted.');
        return null;
    }

    $ext      = $allowed[$mime];
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $dest     = UPLOAD_DIR . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        flash_set('error', 'Image upload failed. Check upload directory permissions.');
        return null;
    }

    return 'uploads/' . $filename;
}

// ══════════════════════════════════════════════════════════════════════════════
// CONTROLLERS
// ══════════════════════════════════════════════════════════════════════════════

function ctrl_home(array $p): void
{
    $articles   = get_published_articles(10);
    $categories = get_all_categories();
    render('home', compact('articles', 'categories'));
}

// ── Category ──────────────────────────────────────────────────────────────────

function ctrl_category(array $p): void
{
    $cat = get_category_by_slug($p['slug'] ?? '');
    if (!$cat) { http_response_code(404); render('404', []); return; }

    $articles   = get_articles_by_category((int)$cat['id'], 12);
    $categories = get_all_categories();
    render('category', compact('cat', 'articles', 'categories'));
}

// ── Article view ──────────────────────────────────────────────────────────────

function ctrl_article_view(array $p): void
{
    $article = get_article_by_id((int)$p['id']);
    if (!$article || $article['status'] !== 'published') {
        http_response_code(404); render('404', []); return;
    }
    $comments   = get_approved_comments((int)$article['id']);
    $categories = get_all_categories();
    render('article', compact('article', 'comments', 'categories'));
}

// ── Comment submit (public) ───────────────────────────────────────────────────

function ctrl_comment_post(array $p): void
{
    csrf_check();
    $article = get_article_by_id((int)$p['id']);
    if (!$article || $article['status'] !== 'published') {
        http_response_code(404); render('404', []); return;
    }

    $name = trim($_POST['author_name'] ?? '');
    $body = trim($_POST['body'] ?? '');

    if ($name === '' || $body === '') {
        flash_set('error', 'Name and comment text are required.');
    } else {
        create_comment((int)$article['id'], $name, $body);
        flash_set('success', 'Your comment has been submitted and is awaiting moderation.');
    }
    redirect('article/' . $article['id']);
}

// ── RSS 2.0 ───────────────────────────────────────────────────────────────────

function ctrl_rss_feed(array $p): void
{
    $articles = get_published_articles(20);
    header('Content-Type: application/rss+xml; charset=UTF-8');
    header('X-Content-Type-Options: nosniff');
    render_raw('feed', compact('articles'));
}

// ── Auth ──────────────────────────────────────────────────────────────────────

function ctrl_login_form(array $p): void
{
    if (current_user()) redirect('dashboard');
    render('login', []);
}

function ctrl_login_post(array $p): void
{
    csrf_check();
    if (current_user()) redirect('dashboard');

    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $st = db()->prepare('SELECT * FROM users WHERE email = ?');
    $st->execute([$email]);
    $user = $st->fetch();

    if (!$user || !password_verify($pass, $user['password_hash'])) {
        flash_set('error', 'Invalid email or password.');
        redirect('login');
    }

    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    flash_set('success', 'Welcome back, ' . $user['name'] . '!');
    redirect($user['role'] === 'editor' ? 'editor/queue' : 'dashboard');
}

function ctrl_logout(array $p): void
{
    session_destroy();
    redirect('');
}

// ── Journalist dashboard ──────────────────────────────────────────────────────

function ctrl_dashboard(array $p): void
{
    $user     = require_login();
    $articles = get_articles_by_author((int)$user['id']);
    render('dashboard', compact('user', 'articles'));
}

// ── Article create ────────────────────────────────────────────────────────────

function ctrl_article_new_form(array $p): void
{
    $user       = require_login();
    $categories = get_all_categories();
    render('article_form', ['user' => $user, 'article' => null, 'categories' => $categories]);
}

function ctrl_article_new_post(array $p): void
{
    $user = require_login();
    csrf_check();

    $title      = trim($_POST['title'] ?? '');
    $body       = trim($_POST['body']  ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);

    if ($title === '' || $body === '' || $categoryId === 0) {
        flash_set('error', 'Title, body, and category are required.');
        redirect('article/new');
    }

    $imagePath = handle_image_upload('image');
    $slug      = unique_slug(slugify($title));

    $id = create_article([
        'title'       => $title,
        'slug'        => $slug,
        'body'        => $body,
        'image_path'  => $imagePath,
        'author_id'   => (int)$user['id'],
        'category_id' => $categoryId,
    ]);

    flash_set('success', 'Article saved as draft.');
    redirect('article/' . $id . '/edit');
}

// ── Article edit ──────────────────────────────────────────────────────────────

function ctrl_article_edit_form(array $p): void
{
    $user    = require_login();
    $article = get_article_by_id((int)$p['id']);

    if (!$article || (int)$article['author_id'] !== (int)$user['id']) {
        http_response_code(403); render('404', ['message' => 'Article not found.']); return;
    }
    if ($article['status'] === 'published') {
        flash_set('error', 'Published articles cannot be edited.');
        redirect('dashboard');
    }

    $categories = get_all_categories();
    render('article_form', compact('user', 'article', 'categories'));
}

function ctrl_article_edit_post(array $p): void
{
    $user = require_login();
    csrf_check();

    $article = get_article_by_id((int)$p['id']);
    if (!$article || (int)$article['author_id'] !== (int)$user['id']) {
        http_response_code(403); render('404', []); return;
    }
    if ($article['status'] === 'published') {
        flash_set('error', 'Published articles cannot be edited.');
        redirect('dashboard');
    }

    $title      = trim($_POST['title'] ?? '');
    $body       = trim($_POST['body']  ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);

    if ($title === '' || $body === '' || $categoryId === 0) {
        flash_set('error', 'Title, body, and category are required.');
        redirect('article/' . $article['id'] . '/edit');
    }

    $imagePath = handle_image_upload('image') ?? $article['image_path'];
    $slug      = unique_slug(slugify($title), (int)$article['id']);

    update_article((int)$article['id'], [
        'title'       => $title,
        'slug'        => $slug,
        'body'        => $body,
        'image_path'  => $imagePath,
        'category_id' => $categoryId,
    ]);

    flash_set('success', 'Article updated.');
    redirect('article/' . $article['id'] . '/edit');
}

// ── Article submit for review ─────────────────────────────────────────────────

function ctrl_article_submit(array $p): void
{
    $user = require_login();
    csrf_check();

    $article = get_article_by_id((int)$p['id']);
    if (!$article || (int)$article['author_id'] !== (int)$user['id']) {
        http_response_code(403); render('404', []); return;
    }
    if ($article['status'] !== 'draft') {
        flash_set('error', 'Only draft articles can be submitted for review.');
        redirect('dashboard');
    }

    submit_article((int)$article['id'], (int)$user['id']);
    flash_set('success', 'Article submitted for editor review.');
    redirect('dashboard');
}

// ── Editor: queue ─────────────────────────────────────────────────────────────

function ctrl_editor_queue(array $p): void
{
    $user     = require_role('editor');
    $articles = get_pending_articles();
    render('editor_queue', compact('user', 'articles'));
}

function ctrl_editor_approve(array $p): void
{
    require_role('editor');
    csrf_check();
    approve_article((int)$p['id']);
    flash_set('success', 'Article approved and published.');
    redirect('editor/queue');
}

function ctrl_editor_reject(array $p): void
{
    require_role('editor');
    csrf_check();
    reject_article((int)$p['id']);
    flash_set('success', 'Article returned to draft.');
    redirect('editor/queue');
}

// ── Editor: comment moderation ────────────────────────────────────────────────

function ctrl_comments_list(array $p): void
{
    $user     = require_role('editor');
    $comments = get_pending_comments();
    render('comments_moderate', compact('user', 'comments'));
}

function ctrl_comment_approve(array $p): void
{
    require_role('editor');
    csrf_check();
    approve_comment((int)$p['id']);
    flash_set('success', 'Comment approved.');
    redirect('editor/comments');
}

function ctrl_comment_delete(array $p): void
{
    require_role('editor');
    csrf_check();
    delete_comment((int)$p['id']);
    flash_set('success', 'Comment deleted.');
    redirect('editor/comments');
}

// ══════════════════════════════════════════════════════════════════════════════
// ROUTE TABLE
// ══════════════════════════════════════════════════════════════════════════════

function register_routes(): void
{
    // Public
    add_route('GET',  '/',                        'ctrl_home');
    add_route('GET',  '/feed.xml',                'ctrl_rss_feed');
    add_route('GET',  '/category/:slug',          'ctrl_category');
    add_route('GET',  '/article/:id',             'ctrl_article_view');
    add_route('POST', '/article/:id/comment',     'ctrl_comment_post');

    // Auth
    add_route('GET',  '/login',                   'ctrl_login_form');
    add_route('POST', '/login',                   'ctrl_login_post');
    add_route('GET',  '/logout',                  'ctrl_logout');

    // Journalist
    add_route('GET',  '/dashboard',               'ctrl_dashboard');
    add_route('GET',  '/article/new',             'ctrl_article_new_form');
    add_route('POST', '/article/new',             'ctrl_article_new_post');
    add_route('GET',  '/article/:id/edit',        'ctrl_article_edit_form');
    add_route('POST', '/article/:id/edit',        'ctrl_article_edit_post');
    add_route('POST', '/article/:id/submit',      'ctrl_article_submit');

    // Editor
    add_route('GET',  '/editor/queue',            'ctrl_editor_queue');
    add_route('POST', '/editor/:id/approve',      'ctrl_editor_approve');
    add_route('POST', '/editor/:id/reject',       'ctrl_editor_reject');
    add_route('GET',  '/editor/comments',         'ctrl_comments_list');
    add_route('POST', '/editor/comment/:id/approve', 'ctrl_comment_approve');
    add_route('POST', '/editor/comment/:id/delete',  'ctrl_comment_delete');
}
