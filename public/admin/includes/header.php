<?php
/** @var string $pageTitle */
/** @var string $activeModule */
$activeModule = $activeModule ?? '';
$navItems = [
    'dashboard' => ['label' => 'Dashboard', 'href' => url('admin/dashboard.php'), 'icon' => 'speedometer2'],
    'gitas' => ['label' => 'Gitas', 'href' => url('admin/modules/gitas/index.php'), 'icon' => 'book'],
    'chapters' => ['label' => 'Chapters', 'href' => url('admin/modules/chapters/index.php'), 'icon' => 'bookmark'],
    'verses' => ['label' => 'Verses', 'href' => url('admin/modules/verses/index.php'), 'icon' => 'quote'],
    'topics' => ['label' => 'Topics', 'href' => url('admin/modules/topics/index.php'), 'icon' => 'tags'],
    'situations' => ['label' => 'Situations', 'href' => url('admin/modules/situations/index.php'), 'icon' => 'compass'],
    'mantras' => ['label' => 'Mantras', 'href' => url('admin/modules/mantras/index.php'), 'icon' => 'soundwave'],
    'teachings' => ['label' => 'Teachings', 'href' => url('admin/modules/teachings/index.php'), 'icon' => 'lightbulb'],
    'daily_wisdom' => ['label' => 'Daily Wisdom', 'href' => url('admin/modules/daily-wisdom/index.php'), 'icon' => 'sun'],
    'settings' => ['label' => 'Settings', 'href' => url('admin/modules/settings/index.php'), 'icon' => 'gear'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? 'Admin') ?> — <?= e(APP_NAME) ?> Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { background: #f5f2ea; }
  .admin-sidebar { min-height: 100vh; background: #2b2620; color: #f5f2ea; }
  .admin-sidebar a { color: #d8cfc0; text-decoration: none; display: block; padding: .6rem 1rem; border-radius: .4rem; }
  .admin-sidebar a.active, .admin-sidebar a:hover { background: #3f382c; color: #fff; }
  .admin-sidebar .brand { color: #fff; font-weight: 700; padding: 1rem; }
</style>
</head>
<body>
<div class="d-flex">
  <nav class="admin-sidebar p-3" style="width:240px; flex-shrink:0;">
    <div class="brand mb-3">🕉 <?= e(APP_NAME) ?></div>
    <?php foreach ($navItems as $key => $item): ?>
      <a href="<?= e($item['href']) ?>" class="<?= $activeModule === $key ? 'active' : '' ?>">
        <i class="bi bi-<?= e($item['icon']) ?> me-2"></i><?= e($item['label']) ?>
      </a>
    <?php endforeach; ?>
    <hr style="border-color:#4a4235;">
    <a href="<?= e(url('')) ?>" target="_blank"><i class="bi bi-box-arrow-up-right me-2"></i>View Site</a>
    <a href="<?= e(url('admin/logout.php')) ?>"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
  </nav>
  <main class="flex-grow-1 p-4">
    <div class="d-flex justify-content-end small text-muted mb-2">
      Signed in as <strong class="ms-1"><?= e(Auth::user()['name'] ?? '') ?></strong>
    </div>
