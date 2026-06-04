<?php

namespace App\Controllers;

use App\Core\Database;

class HomeController
{
    public function index(): void
    {
        $db       = Database::getInstance();
        $featured = $db->query("SELECT * FROM news ORDER BY scraped_at DESC LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
        $latest   = $db->query("SELECT * FROM news ORDER BY scraped_at DESC LIMIT 7 OFFSET 1")->fetchAll(\PDO::FETCH_ASSOC);
        $categories = $db->query("SELECT DISTINCT category FROM news ORDER BY category")->fetchAll(\PDO::FETCH_COLUMN);

        require __DIR__ . '/../Views/home.php';
    }
}
