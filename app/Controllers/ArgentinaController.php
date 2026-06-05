<?php

namespace App\Controllers;

use App\Core\Database;

class ArgentinaController
{
    private int $perPage = 12;

    public function index(): void
    {
        $db   = Database::getInstance();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $this->perPage;

        $total = (int)$db->query("SELECT COUNT(*) FROM news WHERE source_name = 'ESPN Argentina' OR title LIKE '%Argentina%' OR title LIKE '%Boca%' OR title LIKE '%River%' OR title LIKE '%selección%' OR title LIKE '%AFA%'")->fetchColumn();

        $stmt = $db->prepare("SELECT * FROM news WHERE source_name = 'ESPN Argentina' OR title LIKE '%Argentina%' OR title LIKE '%Boca%' OR title LIKE '%River%' OR title LIKE '%selección%' OR title LIKE '%AFA%' ORDER BY scraped_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit',  $this->perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,        \PDO::PARAM_INT);
        $stmt->execute();
        $news = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $totalPages = (int)ceil($total / $this->perPage);

        require __DIR__ . '/../Views/argentina.php';
    }
}
