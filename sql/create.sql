-- The Austro-Asian Times – MySQL/MariaDB production schema
-- Run: mysql -u USER -p DBNAME < sql/create.sql

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name          VARCHAR(100)  NOT NULL,
    email         VARCHAR(150)  NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('journalist','editor') NOT NULL DEFAULT 'journalist',
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
    id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS articles (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title        VARCHAR(255) NOT NULL,
    slug         VARCHAR(255) NOT NULL,
    body         TEXT         NOT NULL,
    image_path   VARCHAR(255)          DEFAULT NULL,
    status       ENUM('draft','pending','published') NOT NULL DEFAULT 'draft',
    author_id    INT UNSIGNED NOT NULL,
    category_id  INT UNSIGNED NOT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_slug (slug),
    KEY idx_status    (status),
    KEY idx_author    (author_id),
    KEY idx_category  (category_id),
    KEY idx_published (published_at),
    CONSTRAINT fk_art_author   FOREIGN KEY (author_id)   REFERENCES users(id),
    CONSTRAINT fk_art_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comments (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    article_id  INT UNSIGNED NOT NULL,
    author_name VARCHAR(100) NOT NULL,
    body        TEXT         NOT NULL,
    status      ENUM('pending','approved') NOT NULL DEFAULT 'pending',
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_article (article_id),
    KEY idx_status  (status),
    CONSTRAINT fk_cmt_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET foreign_key_checks = 1;
