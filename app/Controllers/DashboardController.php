<?php

namespace App\Controllers;

use App\Core\Database;
use App\Services\Scraper;

class DashboardController
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $db = Database::getInstance();

        $totalNews    = (int)$db->query("SELECT COUNT(*) FROM news")->fetchColumn();
        $todayNews    = (int)$db->query("SELECT COUNT(*) FROM news WHERE DATE(scraped_at) = CURDATE()")->fetchColumn();
        $totalUsers   = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $categories   = $db->query("SELECT category, COUNT(*) as cnt FROM news GROUP BY category ORDER BY cnt DESC")->fetchAll(\PDO::FETCH_ASSOC);
        $latestNews   = $db->query("SELECT * FROM news ORDER BY scraped_at DESC LIMIT 20")->fetchAll(\PDO::FETCH_ASSOC);
        $sources      = $db->query("SELECT source_name, COUNT(*) as cnt FROM news GROUP BY source_name ORDER BY cnt DESC")->fetchAll(\PDO::FETCH_ASSOC);

        require __DIR__ . '/../Views/admin/dashboard.php';
    }

    public function scrape(): void
    {
        if (empty($_SESSION['user_id'])) {
            http_response_code(403); echo 'No autorizado'; exit;
        }

        $scraper = new Scraper();
        $result  = $scraper->run();
        $_SESSION['scrape_result'] = $result;
        header('Location: /admin');
        exit;
    }
}
