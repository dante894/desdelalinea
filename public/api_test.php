<?php
header('Content-Type: application/json; charset=utf-8');
function espn($url) {
    $ctx = stream_context_create(['http'=>['timeout'=>15,'ignore_errors'=>true],'ssl'=>['verify_peer'=>false]]);
    $r = @file_get_contents($url, false, $ctx);
    return $r ? json_decode($r, true) : [];
}
$action = $_GET['action'] ?? 'info';
if ($action === 'standings') {
    $d = espn('https://site.api.espn.com/apis/v2/sports/soccer/arg.1/standings');
    $entries = $d['children'][0]['standings']['entries'] ?? [];
    $sample = $entries[0] ?? null;
    echo json_encode(['groups'=>count($d['children']??[]),'teams_group1'=>count($entries),'sample_team'=>$sample['team']['displayName']??null,'stat_names'=>array_column($sample['stats']??[],'name')], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
} elseif ($action === 'scoreboard') {
    $slug = $_GET['slug'] ?? 'arg.1';
    $d = espn("https://site.api.espn.com/apis/site/v2/sports/soccer/{$slug}/scoreboard");
    echo json_encode(['events'=>count($d['events']??[]),'sample'=>isset($d['events'][0])?['name'=>$d['events'][0]['name'],'state'=>$d['events'][0]['competitions'][0]['status']['type']['state']??'?','score'=>($d['events'][0]['competitions'][0]['competitors'][0]['score']??'?').'-'.($d['events'][0]['competitions'][0]['competitors'][1]['score']??'?')]:null], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
} elseif ($action === 'leaders') {
    $d = espn('https://site.api.espn.com/apis/site/v2/sports/soccer/arg.1/leaders');
    echo json_encode(['categories'=>array_column($d['categories']??[],'name'),'sample'=>$d['categories'][0]['leaders'][0]??null], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['tests'=>['standings'=>'?action=standings','scoreboard'=>'?action=scoreboard','copa'=>'?action=scoreboard&slug=arg.copa','primera'=>'?action=scoreboard&slug=arg.2','leaders'=>'?action=leaders']]);
}
