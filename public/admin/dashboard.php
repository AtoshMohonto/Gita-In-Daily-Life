<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
Auth::requireLogin();

$pageTitle = 'Dashboard';
$activeModule = 'dashboard';

$stats = [
    ['label' => 'Gitas', 'count' => Gita::count(), 'href' => url('admin/modules/gitas/index.php'), 'icon' => 'book'],
    ['label' => 'Chapters', 'count' => Chapter::count(), 'href' => url('admin/modules/chapters/index.php'), 'icon' => 'bookmark'],
    ['label' => 'Verses', 'count' => Verse::count(), 'href' => url('admin/modules/verses/index.php'), 'icon' => 'quote'],
    ['label' => 'Teachings', 'count' => Teaching::count(), 'href' => url('admin/modules/teachings/index.php'), 'icon' => 'lightbulb'],
    ['label' => 'Situations', 'count' => Situation::count(), 'href' => url('admin/modules/situations/index.php'), 'icon' => 'compass'],
    ['label' => 'Mantras', 'count' => Mantra::count(), 'href' => url('admin/modules/mantras/index.php'), 'icon' => 'soundwave'],
    ['label' => 'Topics', 'count' => Topic::count(), 'href' => url('admin/modules/topics/index.php'), 'icon' => 'tags'],
    ['label' => 'Published Content', 'count' => Verse::count(['status' => 'published']) + Teaching::count(['status' => 'published']), 'href' => url('admin/modules/verses/index.php'), 'icon' => 'check-circle'],
];

$quickActions = [
    ['label' => '+ Add Gita', 'href' => url('admin/modules/gitas/index.php') . '?action=create'],
    ['label' => '+ Add Chapter', 'href' => url('admin/modules/chapters/index.php') . '?action=create'],
    ['label' => '+ Add Verse', 'href' => url('admin/modules/verses/index.php') . '?action=create'],
    ['label' => '+ Add Teaching', 'href' => url('admin/modules/teachings/index.php') . '?action=create'],
    ['label' => '+ Add Situation', 'href' => url('admin/modules/situations/index.php') . '?action=create'],
    ['label' => '+ Add Mantra', 'href' => url('admin/modules/mantras/index.php') . '?action=create'],
    ['label' => '+ Add Daily Wisdom', 'href' => url('admin/modules/daily-wisdom/index.php') . '?action=create'],
];

require __DIR__ . '/includes/header.php';
?>
<h1 class="h3 mb-4">Dashboard</h1>

<div class="row g-3 mb-4">
  <?php foreach ($stats as $stat): ?>
    <div class="col-6 col-md-3">
      <a href="<?= e($stat['href']) ?>" class="card border-0 shadow-sm p-3 text-decoration-none text-reset d-block h-100">
        <i class="bi bi-<?= e($stat['icon']) ?> fs-4 text-muted"></i>
        <div class="fs-3 fw-bold"><?= (int) $stat['count'] ?></div>
        <div class="text-muted small"><?= e($stat['label']) ?></div>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <div class="col-md-7">
    <div class="card border-0 shadow-sm p-4">
      <h2 class="h6 text-uppercase text-muted mb-3">Quick Actions</h2>
      <div class="d-flex flex-wrap gap-2">
        <?php foreach ($quickActions as $action): ?>
          <a href="<?= e($action['href']) ?>" class="btn btn-sm btn-outline-primary"><?= e($action['label']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card border-0 shadow-sm p-4">
      <h2 class="h6 text-uppercase text-muted mb-3">Recent Activity</h2>
      <ul class="list-unstyled small mb-0">
        <?php foreach (ActivityLog::recent(8) as $log): ?>
          <li class="mb-2 pb-2 border-bottom">
            <strong><?= e($log['user_name'] ?? 'System') ?></strong>
            <?= e($log['action']) ?> <?= e($log['module']) ?>
            <?php if ($log['description']): ?> — <span class="text-muted"><?= e($log['description']) ?></span><?php endif; ?>
            <div class="text-muted" style="font-size:.75rem;"><?= e($log['created_at']) ?></div>
          </li>
        <?php endforeach; ?>
        <?php if (ActivityLog::recent(1) === []): ?>
          <li class="text-muted">No activity yet.</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
