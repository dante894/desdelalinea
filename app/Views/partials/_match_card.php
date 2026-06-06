<?php
// Variables esperadas: $m (array normalizado de normalizeEvent())
$isLive      = $m['state'] === 'in';
$isCompleted = $m['completed'] === true;
$hora        = $m['date'] ? date('d/m H:i', strtotime($m['date']) - 0) : '—'; // ESPN ya en UTC, mostrar como viene
$horaArg     = $m['date'] ? date('d/m H:i', strtotime($m['date']) - 3 * 3600) : '—'; // UTC-3

$cardClass = $isLive ? 'match-card live' : 'match-card';
?>
<div class="<?= $cardClass ?>">
  <div class="team-home <?= ($m['home']['winner'] ?? false) ? 'winner' : '' ?>">
    <?php if ($m['home']['logo']): ?>
    <img class="team-logo-sm" src="<?= htmlspecialchars($m['home']['logo']) ?>" alt="" onerror="this.style.display='none'">
    <?php endif; ?>
    <span class="team-name"><?= htmlspecialchars($m['home']['name']) ?></span>
  </div>

  <div class="score-box">
    <?php if ($isCompleted): ?>
      <div class="score-num"><?= $m['home']['score'] ?> - <?= $m['away']['score'] ?></div>
      <div class="match-status status-ft">Final · <?= $horaArg ?></div>
    <?php elseif ($isLive): ?>
      <div class="score-num" style="color:#e53935"><?= $m['home']['score'] ?? 0 ?> - <?= $m['away']['score'] ?? 0 ?></div>
      <div class="match-status status-live">🔴 <?= htmlspecialchars($m['clock']) ?></div>
    <?php else: ?>
      <div class="score-vs">VS</div>
      <div class="match-status status-ns"><?= $horaArg ?></div>
    <?php endif; ?>
    <?php if ($m['venue']): ?>
    <div class="match-round" style="font-size:.65rem;color:var(--muted);margin-top:.2rem"><?= htmlspecialchars($m['venue']) ?></div>
    <?php endif; ?>
  </div>

  <div class="team-away <?= ($m['away']['winner'] ?? false) ? 'winner' : '' ?>">
    <?php if ($m['away']['logo']): ?>
    <img class="team-logo-sm" src="<?= htmlspecialchars($m['away']['logo']) ?>" alt="" onerror="this.style.display='none'">
    <?php endif; ?>
    <span class="team-name"><?= htmlspecialchars($m['away']['name']) ?></span>
  </div>
</div>
