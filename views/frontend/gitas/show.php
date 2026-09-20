<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('gitas')) ?>">Gitas</a></li>
    <li class="breadcrumb-item active"><?= e($gita['name']) ?></li>
  </ol></nav>

  <h1 class="h2 font-serif mb-1"><?= e($gita['name']) ?></h1>
  <?php if ($gita['alternate_names']): ?><p class="text-muted small mb-3">Also known as: <?= e($gita['alternate_names']) ?></p><?php endif; ?>
  <?php if ($gita['author_attribution']): ?><p class="text-muted small mb-4"><?= e($gita['author_attribution']) ?></p><?php endif; ?>

  <div class="row">
    <div class="col-lg-8">
      <?php if ($gita['introduction']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-1">Introduction</div>
          <p><?= nl2br(e($gita['introduction'])) ?></p>
        </div>
      <?php endif; ?>
      <?php if ($gita['philosophy']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-1">Philosophy</div>
          <p><?= nl2br(e($gita['philosophy'])) ?></p>
        </div>
      <?php endif; ?>
      <?php if ($gita['historical_context']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-1">Historical / Traditional Context</div>
          <p><?= nl2br(e($gita['historical_context'])) ?></p>
        </div>
      <?php endif; ?>
      <?php if ($gita['main_themes']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-1">Main Themes</div>
          <p><?= nl2br(e($gita['main_themes'])) ?></p>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-lg-4">
      <div class="card-gidl p-4">
        <h2 class="h6 text-uppercase text-muted mb-3">Chapters</h2>
        <ul class="list-unstyled mb-0">
          <?php foreach ($chapters as $chapter): ?>
            <li class="mb-2">
              <a href="<?= e(url('gitas/' . $gita['slug'] . '/chapter/' . $chapter['chapter_number'])) ?>">
                Chapter <?= (int) $chapter['chapter_number'] ?>: <?= e($chapter['name']) ?>
              </a>
              <span class="text-muted small"> — <?= (int) Chapter::verseCount((int) $chapter['id']) ?> verses</span>
            </li>
          <?php endforeach; ?>
          <?php if ($chapters === []): ?>
            <li class="text-muted small">No chapters published yet.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
