<!DOCTYPE html>
<html lang="<?= e(current_lang()) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? APP_NAME) ?></title>
<meta name="description" content="<?= e($metaDescription ?? '') ?>">
<meta property="og:title" content="<?= e($pageTitle ?? APP_NAME) ?>">
<meta property="og:description" content="<?= e($metaDescription ?? '') ?>">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary">
<link rel="icon" href="data:,">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@500;600;700&family=Noto+Sans:wght@400;500;600&family=Noto+Sans+Bengali:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="<?= e(asset('css/style.css')) ?>" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-gidl sticky-top py-3">
  <div class="container">
    <a class="navbar-brand" href="<?= e(url('')) ?>">🕉 <?= e(APP_NAME) ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#gidlNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="gidlNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?= e(url('gitas')) ?>"><?= e(__t('nav.gitas')) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= e(url('topics')) ?>"><?= e(__t('nav.topics')) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= e(url('situations')) ?>"><?= e(__t('nav.situations')) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= e(url('mantras')) ?>"><?= e(__t('nav.mantras')) ?></a></li>
        <li class="nav-item"><a class="nav-link" href="<?= e(url('daily-wisdom')) ?>"><?= e(__t('nav.daily_wisdom')) ?></a></li>
      </ul>
      <form class="d-flex me-2" role="search" action="<?= e(url('search')) ?>" method="get">
        <input class="form-control form-control-sm" type="search" name="q" placeholder="<?= e(__t('search.placeholder')) ?>" value="<?= e(Request::query('q', '')) ?>" style="min-width:220px;">
      </form>
      <div class="btn-group btn-group-sm" role="group">
        <a href="<?= e(url('lang/en')) ?>" class="btn btn-outline-secondary <?= current_lang() === 'en' ? 'active' : '' ?>">EN</a>
        <a href="<?= e(url('lang/bn')) ?>" class="btn btn-outline-secondary <?= current_lang() === 'bn' ? 'active' : '' ?>">বাং</a>
      </div>
    </div>
  </div>
</nav>
