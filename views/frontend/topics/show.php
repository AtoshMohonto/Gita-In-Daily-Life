<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('topics')) ?>">Topics</a></li>
    <li class="breadcrumb-item active"><?= e($topic['name']) ?></li>
  </ol></nav>

  <h1 class="h2 font-serif mb-2"><?= e($topic['name']) ?></h1>
  <p class="text-muted mb-5"><?= e($topic['description']) ?></p>

  <h2 class="h5 mb-3">Verses Across the Gitas</h2>
  <p class="text-muted small mb-4">Teachings on this topic are shown as they appear in each text — the Gitas are not forced to agree.</p>
  <div class="row g-3 mb-5">
    <?php foreach ($verses as $verse): ?>
      <div class="col-md-6">
        <a href="<?= e(url('verses/' . $verse['gita_slug'] . '/' . $verse['chapter_number'] . '/' . $verse['verse_number'])) ?>" class="card-gidl p-3 d-block text-decoration-none text-reset h-100">
          <div class="small text-muted mb-1"><?= e($verse['gita_name']) ?> • <?= (int) $verse['chapter_number'] ?>.<?= (int) $verse['verse_number'] ?></div>
          <p class="mb-0"><?= e(str_excerpt($verse['simple_translation_en'], 130)) ?></p>
        </a>
      </div>
    <?php endforeach; ?>
    <?php if ($verses === []): ?><p class="text-muted">No verses tagged with this topic yet.</p><?php endif; ?>
  </div>

  <?php if ($teachings !== []): ?>
    <h2 class="h5 mb-3"><?= e(__t('label.practical_application')) ?></h2>
    <div class="row g-3">
      <?php foreach ($teachings as $teaching): ?>
        <div class="col-md-6">
          <div class="card-gidl p-3 h-100">
            <p class="fw-semibold mb-1"><?= e($teaching['title']) ?></p>
            <p class="text-muted small mb-0"><?= e(str_excerpt($teaching['how_to_apply'], 140)) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
