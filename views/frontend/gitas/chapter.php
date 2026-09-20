<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('gitas')) ?>">Gitas</a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('gitas/' . $gita['slug'])) ?>"><?= e($gita['name']) ?></a></li>
    <li class="breadcrumb-item active">Chapter <?= (int) $chapter['chapter_number'] ?></li>
  </ol></nav>

  <h1 class="h2 font-serif mb-1">Chapter <?= (int) $chapter['chapter_number'] ?>: <?= e($chapter['name']) ?></h1>
  <?php if ($chapter['sanskrit_name']): ?><p class="verse-sanskrit mb-1"><?= e($chapter['sanskrit_name']) ?></p><?php endif; ?>
  <?php if ($chapter['translation_name']): ?><p class="text-muted mb-4">"<?= e($chapter['translation_name']) ?>"</p><?php endif; ?>

  <?php if ($chapter['summary']): ?>
    <div class="content-block">
      <div class="badge-label text-muted mb-1">Summary</div>
      <p><?= nl2br(e($chapter['summary'])) ?></p>
    </div>
  <?php endif; ?>

  <h2 class="h5 mt-5 mb-3">Verses</h2>
  <div class="list-group">
    <?php foreach ($verses as $verse): ?>
      <a href="<?= e(url('verses/' . $gita['slug'] . '/' . $chapter['chapter_number'] . '/' . $verse['verse_number'])) ?>" class="list-group-item list-group-item-action py-3">
        <div class="d-flex justify-content-between">
          <span class="fw-semibold">Verse <?= (int) $verse['verse_number'] ?></span>
          <?php if ((int) $verse['featured'] === 1): ?><span class="badge bg-info text-dark">Featured</span><?php endif; ?>
        </div>
        <p class="text-muted small mb-0 mt-1"><?= e(str_excerpt($verse['simple_translation_en'], 140)) ?></p>
      </a>
    <?php endforeach; ?>
    <?php if ($verses === []): ?>
      <p class="text-muted">No verses published for this chapter yet.</p>
    <?php endif; ?>
  </div>
</div>
