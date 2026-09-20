<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('situations')) ?>">Life Situations</a></li>
    <li class="breadcrumb-item active"><?= e($situation['name']) ?></li>
  </ol></nav>

  <div class="text-center mb-5">
    <i class="bi bi-<?= e($situation['icon'] ?: 'compass') ?> display-4 mb-3 d-block" style="color:var(--gidl-accent);"></i>
    <h1 class="h2 font-serif"><?= e($situation['name']) ?></h1>
    <?php if ($situation['description']): ?><p class="text-muted mx-auto" style="max-width:560px;"><?= e($situation['description']) ?></p><?php endif; ?>
  </div>

  <?php if ($teachings !== []): ?>
    <h2 class="h5 mb-3"><?= e(__t('label.practical_application')) ?></h2>
    <div class="row g-3 mb-5">
      <?php foreach ($teachings as $teaching): ?>
        <div class="col-md-6">
          <div class="card-gidl p-4 h-100">
            <p class="fw-semibold mb-2"><?= e($teaching['title']) ?></p>
            <p class="text-muted small mb-2"><?= nl2br(e($teaching['how_to_apply'])) ?></p>
            <div class="content-block mt-3 mb-0">
              <div class="badge-label text-muted mb-1"><?= e(__t('label.reflection')) ?></div>
              <p class="fst-italic small mb-0">"<?= e($teaching['reflection_question']) ?>"</p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($verses !== []): ?>
    <h2 class="h5 mb-3">Relevant Verses</h2>
    <div class="row g-3 mb-5">
      <?php foreach ($verses as $verse): ?>
        <div class="col-md-6">
          <a href="<?= e(url('verses/' . $verse['gita_slug'] . '/' . $verse['chapter_number'] . '/' . $verse['verse_number'])) ?>" class="card-gidl p-3 d-block text-decoration-none text-reset h-100">
            <div class="small text-muted mb-1"><?= e($verse['gita_name']) ?> • <?= (int) $verse['chapter_number'] ?>.<?= (int) $verse['verse_number'] ?></div>
            <p class="mb-0"><?= e(str_excerpt($verse['simple_translation_en'], 130)) ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($mantras !== []): ?>
    <h2 class="h5 mb-3">Related Mantras</h2>
    <div class="row g-3">
      <?php foreach ($mantras as $mantra): ?>
        <div class="col-md-6">
          <a href="<?= e(url('mantras/' . $mantra['slug'])) ?>" class="card-gidl p-3 d-block text-decoration-none text-reset h-100">
            <p class="fw-semibold mb-1"><?= e($mantra['title']) ?></p>
            <p class="text-muted small mb-0"><?= e(str_excerpt($mantra['meaning'], 110)) ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($teachings === [] && $verses === [] && $mantras === []): ?>
    <p class="text-muted text-center">No content has been connected to this situation yet.</p>
  <?php endif; ?>
</div>
