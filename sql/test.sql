-- The Austro-Asian Times – verification queries
-- Run after load.sql to confirm seed data is correct

SELECT 'users' AS tbl, COUNT(*) AS rows FROM users
UNION ALL
SELECT 'categories', COUNT(*) FROM categories
UNION ALL
SELECT 'articles',   COUNT(*) FROM articles
UNION ALL
SELECT 'comments',   COUNT(*) FROM comments;

-- Confirm role distribution
SELECT role, COUNT(*) FROM users GROUP BY role;

-- Confirm article status distribution
SELECT status, COUNT(*) FROM articles GROUP BY status;

-- Confirm comment status distribution
SELECT status, COUNT(*) FROM comments GROUP BY status;

-- Spot-check published articles with author and category
SELECT a.title, u.name AS author, c.name AS category, a.status
FROM articles a
JOIN users u ON u.id = a.author_id
JOIN categories c ON c.id = a.category_id
ORDER BY a.id;
