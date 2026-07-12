<?php

namespace App\Controllers;

use App\Core\Database;

class NewsController
{
    private int $perPage = 12;

<<<<<<< HEAD
    // Categorías válidas: Mundial 2026 y fútbol local argentino
    private array $validCats = ['Mundial 2026', 'Argentina'];
=======
    // Categorías válidas (solo fútbol)
    private array $validCats = ['Argentina', 'Europa', 'Fichajes', 'Internacional'];
>>>>>>> cb116038913c643d0c8c68dd276ebb93c78d7470

    public function index(): void
    {
        $db       = Database::getInstance();
        $category = $_GET['cat'] ?? '';
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $offset   = ($page - 1) * $this->perPage;

        // Validar que la categoría sea de fútbol
        if ($category && !in_array($category, $this->validCats, true)) {
            $category = '';
        }

        if ($category) {
            $where  = "WHERE category = :cat";
            $params = [':cat' => $category];
        } else {
            // Todas las categorías de fútbol (excluye restos viejos)
            $placeholders = implode(',', array_fill(0, count($this->validCats), '?'));
            $where  = "WHERE category IN ($placeholders)";
            $params = $this->validCats;
        }

        $stmt = $db->prepare("SELECT COUNT(*) FROM news $where");
        $stmt->execute(array_values($params));
        $total = (int)$stmt->fetchColumn();

        $stmt2 = $db->prepare("SELECT * FROM news $where ORDER BY scraped_at DESC LIMIT :limit OFFSET :offset");
        if ($category) {
            $stmt2->bindValue(':cat', $category);
        } else {
            foreach (array_values($this->validCats) as $i => $cat) {
                $stmt2->bindValue($i + 1, $cat);
            }
        }
        $stmt2->bindValue(':limit',  $this->perPage, \PDO::PARAM_INT);
        $stmt2->bindValue(':offset', $offset,        \PDO::PARAM_INT);
        $stmt2->execute();
        $news = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

        $categories = $this->validCats;

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