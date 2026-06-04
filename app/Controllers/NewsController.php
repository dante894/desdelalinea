<?php

namespace App\Controllers;

use App\Core\Database;

class NewsController
{
    private int $perPage = 12;

    public function index(): void
    {
        $db       = Database::getInstance();
        $category = $_GET['cat'] ?? '';
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $offset   = ($page - 1) * $this->perPage;

        $where  = $category ? "WHERE category = :cat" : '';
        $params = $category ? [':cat' => $category] : [];

        $total = (int)$db->prepare("SELECT COUNT(*) FROM news $where")->execute($params) ?: 0;
        $stmt  = $db->prepare("SELECT COUNT(*) FROM news $where");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt2 = $db->prepare("SELECT * FROM news $where ORDER BY scraped_at DESC LIMIT :limit OFFSET :offset");
        if ($category) $stmt2->bindValue(':cat', $category);
        $stmt2->bindValue(':limit',  $this->perPage, \PDO::PARAM_INT);
        $stmt2->bindValue(':offset', $offset,        \PDO::PARAM_INT);
        $stmt2->execute();
        $news = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

        $catStmt = $db->query("SELECT DISTINCT category FROM news ORDER BY category");
        $categories = $catStmt->fetchAll(\PDO::FETCH_COLUMN);

        $totalPages = (int)ceil($total / $this->perPage);

        require __DIR__ . '/../Views/news/index.php';
    }

    public function show(): void
    {
        $db   = Database::getInstance();
        $slug = $_GET['slug'] ?? '';
        $id   = (int)($_GET['id'] ?? 0);

        if ($id) {
            $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
            $stmt->execute([$id]);
        } else {
            $stmt = $db->prepare("SELECT * FROM news WHERE slug = ?");
            $stmt->execute([$slug]);
        }
        $article = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$article) {
            http_response_code(404);
            echo '<h1>Noticia no encontrada</h1>';
            return;
        }

        // Related
        $rel = $db->prepare("SELECT id, title, slug, image_url, source_name, scraped_at FROM news WHERE category = ? AND id != ? ORDER BY scraped_at DESC LIMIT 4");
        $rel->execute([$article['category'], $article['id']]);
        $related = $rel->fetchAll(\PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/news/show.php';
    }
}
