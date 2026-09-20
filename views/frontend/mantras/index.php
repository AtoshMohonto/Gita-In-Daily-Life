<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li><li class="breadcrumb-item active">Mantras</li></ol></nav>
  <h1 class="h2 font-serif mb-4">Mantras</h1>
  <div class="row g-3">
    <?php foreach ($mantras as $mantra): ?>
      <div class="col-md-6">
        <a href="<?= e(url('mantras/' . $mantra['slug'])) ?>" class="card-gidl p-4 d-block text-decoration-none text-reset h-100">
          <h2 class="h5 font-serif"><?= e($mantra['title']) ?></h2>
          <p class="verse-sanskrit fs-6 mb-2"><?= e(str_excerpt($mantra['sanskrit'], 80)) ?></p>
          <p class="text-muted small mb-0"><?= e(str_excerpt($mantra['meaning'], 110)) ?></p>
        </a>
      </div>
    <?php endforeach; ?>
    <?php if ($mantras === []): ?><p class="text-muted">No mantras published yet.</p><?php endif; ?>
  </div>
</div>
