<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= e(__t('breadcrumb.home')) ?></a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('mantras')) ?>"><?= e(__t('breadcrumb.mantras')) ?></a></li>
    <li class="breadcrumb-item active"><?= e($mantra['title']) ?></li>
  </ol></nav>

  <div class="row">
    <div class="col-lg-8">
      <h1 class="h2 font-serif mb-4"><?= e($mantra['title']) ?></h1>

      <div class="content-block">
        <div class="badge-label text-muted mb-2"><?= e(__t('label.original_text')) ?></div>
        <p class="verse-sanskrit"><?= nl2br(e($mantra['sanskrit'])) ?></p>
        <?php if ($mantra['transliteration']): ?><p class="fst-italic text-muted"><?= nl2br(e($mantra['transliteration'])) ?></p><?php endif; ?>
      </div>

      <div class="content-block">
        <div class="badge-label text-muted mb-2"><?= e(__t('label.translation')) ?></div>
        <p class="fs-5"><?= nl2br(e(mantra_text($mantra))) ?></p>
        <?php if (current_lang() === 'bn' && $mantra['meaning']): ?>
          <p class="text-muted small">English: <?= nl2br(e($mantra['meaning'])) ?></p>
        <?php elseif (current_lang() !== 'bn' && !empty($mantra['meaning_bn'])): ?>
          <p class="text-muted small">বাংলা: <?= nl2br(e($mantra['meaning_bn'])) ?></p>
        <?php endif; ?>
      </div>

      <?php if ($mantra['purpose']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-2"><?= e(__t('mantras.traditional_use')) ?></div>
          <p><?= e($mantra['purpose']) ?></p>
          <?php if ($mantra['traditionally_recited']): ?><p class="text-muted small mb-0"><?= e(__t('mantras.traditionally_recited')) ?> <?= e($mantra['traditionally_recited']) ?></p><?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($mantra['source']): ?>
        <p class="text-muted small"><?= e(__t('verse.source_heading')) ?>: <?= e($mantra['source']) ?></p>
      <?php endif; ?>

      <div class="alert alert-light border small mt-4">
        <?= e(__t('mantras.disclaimer')) ?>
      </div>
    </div>

    <div class="col-lg-4">
      <?php if ($situations !== []): ?>
        <div class="mb-4">
          <h2 class="h6 text-uppercase text-muted"><?= e(__t('mantras.related_situations')) ?></h2>
          <ul class="list-unstyled">
            <?php foreach ($situations as $situation): ?>
              <li><a href="<?= e(url('situations/' . $situation['slug'])) ?>"><?= e($situation['name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
