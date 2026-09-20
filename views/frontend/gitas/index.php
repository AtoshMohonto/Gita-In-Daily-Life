<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li><li class="breadcrumb-item active">Gitas</li></ol></nav>

  <h1 class="h2 mb-4 font-serif">Explore the Gitas</h1>

  <form method="get" class="mb-4" style="max-width:400px;">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search Gitas..." value="<?= e($q) ?>">
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
              <?= (int) Gita::chapterCount((int) $gita['id']) ?> chapters &middot;
              <?= (int) Gita::verseCount((int) $gita['id']) ?> verses
            </div>
            <span class="btn btn-sm btn-outline-primary">View Chapters</span>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
    <?php if ($gitas === []): ?>
      <p class="text-muted">No Gitas found.</p>
    <?php endif; ?>
  </div>
</div>
