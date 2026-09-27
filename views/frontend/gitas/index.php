<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= e(__t('breadcrumb.home')) ?></a></li><li class="breadcrumb-item active"><?= e(__t('breadcrumb.gitas')) ?></li></ol></nav>

  <h1 class="h2 mb-4 font-serif"><?= e(__t('gitas.page_title')) ?></h1>

  <form method="get" class="mb-4" style="max-width:400px;">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="<?= e(__t('gitas.search_placeholder')) ?>" value="<?= e($q) ?>">
      <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <div class="row g-4">
    <?php foreach ($gitas as $gita): ?>
      <div class="col-md-4">
        <a href="<?= e(url('gitas/' . $gita['slug'])) ?>" class="text-decoration-none text-reset">
          <div class="card-gidl p-4 h-100">
            <h2 class="h5 font-serif"><?= e($gita['name']) ?></h2>
            <p class="text-muted small mb-3"><?= e(str_excerpt($gita['short_description'], 120)) ?></p>
            <div class="small text-muted mb-2">
              <?= (int) Gita::chapterCount((int) $gita['id']) ?> <?= e(__t('gitas.chapters_suffix')) ?> &middot;
              <?= (int) Gita::verseCount((int) $gita['id']) ?> <?= e(__t('gitas.verses_suffix')) ?>
            </div>
            <span class="btn btn-sm btn-outline-primary"><?= e(__t('action.view_chapters')) ?></span>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
    <?php if ($gitas === []): ?>
      <p class="text-muted"><?= e(__t('gitas.none_found')) ?></p>
    <?php endif; ?>
  </div>
</div>
