<div class="container py-5">
  <h1 class="h2 font-serif mb-1"><?= e(__t('search.results_for')) ?> "<?= e($q) ?>"</h1>
  <p class="text-muted mb-5"><?= (int) $totalResults ?> result<?= $totalResults === 1 ? '' : 's' ?></p>

  <?php if ($q === ''): ?>
    <p class="text-muted">Type something in the search box above to get started.</p>
  <?php endif; ?>

  <?php if ($results['gitas'] !== []): ?>
    <h2 class="h5 mb-3">Gitas</h2>
    <div class="row g-3 mb-5">
      <?php foreach ($results['gitas'] as $gita): ?>
        <div class="col-md-6">
          <a href="<?= e(url('gitas/' . $gita['slug'])) ?>" class="card-gidl p-3 d-block text-decoration-none text-reset">
            <p class="fw-semibold mb-1"><?= e($gita['name']) ?></p>
            <p class="text-muted small mb-0"><?= e(str_excerpt($gita['short_description'], 110)) ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($results['verses'] !== []): ?>
    <h2 class="h5 mb-3">Verses</h2>
    <div class="row g-3 mb-5">
      <?php foreach ($results['verses'] as $verse): ?>
        <div class="col-md-6">
          <a href="<?= e(url('verses/' . $verse['gita_slug'] . '/' . $verse['chapter_number'] . '/' . $verse['verse_number'])) ?>" class="card-gidl p-3 d-block text-decoration-none text-reset">
            <div class="small text-muted mb-1"><?= e($verse['gita_name']) ?> • <?= (int) $verse['chapter_number'] ?>.<?= (int) $verse['verse_number'] ?></div>
            <p class="mb-0"><?= e(str_excerpt($verse['simple_translation_en'], 120)) ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($results['teachings'] !== []): ?>
    <h2 class="h5 mb-3">Practical Teachings</h2>
    <div class="row g-3 mb-5">
      <?php foreach ($results['teachings'] as $teaching): ?>
        <div class="col-md-6">
          <div class="card-gidl p-3">
            <p class="fw-semibold mb-1"><?= e($teaching['title']) ?></p>
            <p class="text-muted small mb-0"><?= e(str_excerpt($teaching['life_problem'], 110)) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($results['topics'] !== []): ?>
    <h2 class="h5 mb-3">Topics</h2>
    <div class="d-flex flex-wrap gap-2 mb-5">
      <?php foreach ($results['topics'] as $topic): ?>
        <a href="<?= e(url('topics/' . $topic['slug'])) ?>" class="btn btn-sm btn-outline-secondary rounded-pill"><?= e($topic['name']) ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($results['situations'] !== []): ?>
    <h2 class="h5 mb-3">Life Situations</h2>
    <div class="d-flex flex-wrap gap-2 mb-5">
      <?php foreach ($results['situations'] as $situation): ?>
        <a href="<?= e(url('situations/' . $situation['slug'])) ?>" class="btn btn-sm btn-outline-secondary rounded-pill"><?= e($situation['name']) ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($results['mantras'] !== []): ?>
    <h2 class="h5 mb-3">Mantras</h2>
    <div class="row g-3 mb-5">
      <?php foreach ($results['mantras'] as $mantra): ?>
        <div class="col-md-6">
          <a href="<?= e(url('mantras/' . $mantra['slug'])) ?>" class="card-gidl p-3 d-block text-decoration-none text-reset">
            <p class="fw-semibold mb-1"><?= e($mantra['title']) ?></p>
            <p class="text-muted small mb-0"><?= e(str_excerpt($mantra['meaning'], 110)) ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($q !== '' && $totalResults === 0): ?>
    <p class="text-muted">No results found. Try a different word.</p>
  <?php endif; ?>
</div>
