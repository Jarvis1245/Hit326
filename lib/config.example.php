<?php
// Copy this file to lib/config.php and fill in your values.
// lib/config.php is git-ignored and must NEVER be committed with real credentials.

// ── Database ──────────────────────────────────────────────────────────────────
// MySQL/MariaDB (shared hosting):
define('DB_DSN',  'mysql:host=localhost;dbname=YOUR_DBNAME;charset=utf8mb4');
define('DB_USER', 'YOUR_DB_USER');
define('DB_PASS', 'YOUR_DB_PASSWORD');

// SQLite (local dev – uncomment and comment out MySQL lines above):
// define('DB_DSN',  'sqlite:' . __DIR__ . '/../storage/aatimes.db');
// define('DB_USER', null);
// define('DB_PASS', null);

// ── URLs ──────────────────────────────────────────────────────────────────────
// No trailing slash. For shared hosting sub-folder:
// define('BASE_URL', 'https://yourdomain.com/aatimes');
define('BASE_URL', 'http://localhost:8080');

// ── Paths (do not change unless you restructure the project) ──────────────────
define('UPLOAD_DIR', __DIR__ . '/../public/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');
define('VIEWS',      __DIR__ . '/../views');
define('APP_NAME',   'The Austro-Asian Times');
