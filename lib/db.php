<?php

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    if (str_starts_with(DB_DSN, 'sqlite:')) {
        $pdo->exec('PRAGMA foreign_keys = ON;');
        $pdo->exec('PRAGMA journal_mode = WAL;');
    }

    return $pdo;
}

// Returns the SQL expression for the current timestamp, compatible with both
// MySQL ("NOW()") and SQLite ("datetime('now')").
function db_now(): string
{
    return str_starts_with(DB_DSN, 'sqlite:') ? "datetime('now')" : 'NOW()';
}
