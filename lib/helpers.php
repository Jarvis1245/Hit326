<?php

// ── Output escaping ───────────────────────────────────────────────────────────

function esc(?string $v): string
{
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ── URL helpers ───────────────────────────────────────────────────────────────

function base_url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

// ── Redirect ──────────────────────────────────────────────────────────────────

function redirect(string $path): never
{
    header('Location: ' . base_url($path));
    exit;
}

// ── Flash messages ────────────────────────────────────────────────────────────

function flash_set(string $key, string $msg): void
{
    $_SESSION['flash'][$key] = $msg;
}

function flash_get(string $key): ?string
{
    $v = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $v;
}

// ── CSRF ──────────────────────────────────────────────────────────────────────

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . esc(csrf_token()) . '">';
}

function csrf_check(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrf_token(), $token)) {
        http_response_code(403);
        die('Invalid CSRF token. Please go back and try again.');
    }
}

// ── Template rendering ────────────────────────────────────────────────────────

// Render $view wrapped in views/layout.php, injecting $data into scope.
function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    include VIEWS . '/' . $view . '.php';
    $content = ob_get_clean();
    include VIEWS . '/layout.php';
}

// Render without layout wrapper (used for RSS/XML responses).
function render_raw(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    include VIEWS . '/' . $view . '.php';
}

// ── Time helpers ──────────────────────────────────────────────────────────────

function time_ago(string $ts): string
{
    $diff = time() - (int) strtotime($ts);
    if ($diff < 60)        return 'just now';
    if ($diff < 3600)      return (int)($diff / 60) . ' min ago';
    if ($diff < 86400)     return (int)($diff / 3600) . ' hr ago';
    if ($diff < 604800)    return (int)($diff / 86400) . ' days ago';
    return date('j M Y', (int) strtotime($ts));
}

function format_date(string $ts): string
{
    return date('j F Y', (int) strtotime($ts));
}

// ── Slug generation ───────────────────────────────────────────────────────────

function slugify(string $text): string
{
    $text = mb_strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// Ensure slug is unique in articles table, appending -2, -3 … if needed.
function unique_slug(string $base, ?int $excludeId = null): string
{
    $slug = $base;
    $n = 1;
    while (true) {
        $sql = 'SELECT id FROM articles WHERE slug = ?';
        $args = [$slug];
        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
            $args[] = $excludeId;
        }
        $st = db()->prepare($sql);
        $st->execute($args);
        if (!$st->fetch()) break;
        $n++;
        $slug = $base . '-' . $n;
    }
    return $slug;
}

// ── Auth helpers ──────────────────────────────────────────────────────────────

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) return null;
    static $user = null;
    if ($user !== null) return $user;
    $st = db()->prepare('SELECT id, name, email, role FROM users WHERE id = ?');
    $st->execute([$_SESSION['user_id']]);
    $user = $st->fetch() ?: null;
    return $user;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        flash_set('error', 'You must be logged in to access that page.');
        redirect('login');
    }
    return $user;
}

function require_role(string $role): array
{
    $user = require_login();
    if ($user['role'] !== $role) {
        http_response_code(403);
        render('404', ['message' => 'You do not have permission to access that page.']);
        exit;
    }
    return $user;
}
