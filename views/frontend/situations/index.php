<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li><li class="breadcrumb-item active">Life Situations</li></ol></nav>
  <h1 class="h2 font-serif mb-2">What Are You Facing Today?</h1>
  <p class="text-muted mb-4">Choose what best describes how you feel right now, and see teachings, verses and mantras connected to it.</p>
  <div class="row g-3">
    <?php foreach ($situations as $situation): ?>
      <div class="col-6 col-md-3">
        <a class="situation-tile" href="<?= e(url('situations/' . $situation['slug'])) ?>">
          <i class="bi bi-<?= e($situation['icon'] ?: 'compass') ?> fs-3 mb-2 d-block" style="color:var(--gidl-accent);"></i>
          <div class="fw-semibold"><?= e($situation['name']) ?></div>
          <?php if ($situation['description']): ?><div class="small text-muted mt-1"><?= e($situation['description']) ?></div><?php endif; ?>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
