<?php

namespace App\Services;

class FootballApi
{
    private string $apiKey = '2854b2e9a847aec77765f23b9bcba33c';
    private string $base   = 'https://api.football-data.org/v4';

    // ID del Mundial 2026 en football-data.org
    private int $worldCupId = 2000;

    private function get(string $endpoint): ?array
    {
        $ctx = stream_context_create([
            'http' => [
                'timeout'    => 10,
                'user_agent' => 'DesdeLaLinea/1.0',
                'header'     => "X-Auth-Token: {$this->apiKey}\r\n",
            ],
            'ssl' => ['verify_peer' => false],
        ]);

        $resp = @file_get_contents($this->base . $endpoint, false, $ctx);
        if (!$resp) return null;
        return json_decode($resp, true);
    }

    public function getStandings(): array
    {
        $data = $this->get("/competitions/{$this->worldCupId}/standings");
        return $data['standings'] ?? [];
    }

    public function getMatches(string $status = ''): array
    {
        $qs   = $status ? "?status={$status}" : '';
        $data = $this->get("/competitions/{$this->worldCupId}/matches{$qs}");
        return $data['matches'] ?? [];
    }

    public function getRecentMatches(): array
    {
        $data = $this->get("/competitions/{$this->worldCupId}/matches?status=FINISHED");
        $matches = $data['matches'] ?? [];
        return array_slice(array_reverse($matches), 0, 10);
    }

    public function getUpcomingMatches(): array
    {
        $data = $this->get("/competitions/{$this->worldCupId}/matches?status=SCHEDULED");
        return array_slice($data['matches'] ?? [], 0, 10);
    }

    public function getLiveMatches(): array
    {
        $data = $this->get("/competitions/{$this->worldCupId}/matches?status=IN_PLAY,PAUSED");
        return $data['matches'] ?? [];
    }
}
