<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= e(__t('breadcrumb.home')) ?></a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('gitas/' . $verse['gita_slug'])) ?>"><?= e($verse['gita_name']) ?></a></li>
    <li class="breadcrumb-item"><a href="<?= e(url('gitas/' . $verse['gita_slug'] . '/chapter/' . $verse['chapter_number'])) ?>"><?= e(__t('chapter.label')) ?> <?= (int) $verse['chapter_number'] ?></a></li>
    <li class="breadcrumb-item active"><?= e(__t('chapter.verse_label')) ?> <?= (int) $verse['verse_number'] ?></li>
  </ol></nav>

  <div class="row">
    <div class="col-lg-8">
      <h1 class="h3 mb-4"><?= e($verse['gita_name']) ?> — <?= e(__t('chapter.label')) ?> <?= (int) $verse['chapter_number'] ?>, <?= e(__t('chapter.verse_label')) ?> <?= (int) $verse['verse_number'] ?></h1>

      <?php if ($verse['sanskrit_text']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-2"><?= e(__t('label.original_text')) ?></div>
          <p class="verse-sanskrit"><?= nl2br(e($verse['sanskrit_text'])) ?></p>
          <?php if ($verse['transliteration']): ?>
            <p class="fst-italic text-muted"><?= nl2br(e($verse['transliteration'])) ?></p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($verse['simple_translation_en'] || $verse['translation_bn'] || $verse['literal_translation']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-2"><?= e(__t('label.translation')) ?></div>
          <?php if (verse_text($verse)): ?><p class="fs-5"><?= nl2br(e(verse_text($verse))) ?></p><?php endif; ?>
          <?php if (current_lang() === 'bn' && $verse['simple_translation_en']): ?>
            <p class="text-muted small">English: <?= nl2br(e($verse['simple_translation_en'])) ?></p>
          <?php elseif (current_lang() !== 'bn' && $verse['translation_bn']): ?>
            <p class="text-muted small">বাংলা: <?= nl2br(e($verse['translation_bn'])) ?></p>
          <?php endif; ?>
          <?php if ($verse['literal_translation']): ?><p class="text-muted small mb-0"><?= e(__t('verse.literal')) ?> <?= nl2br(e($verse['literal_translation'])) ?></p><?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($verse['explanation'] || $verse['philosophical_meaning']): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-2"><?= e(__t('label.commentary')) ?></div>
          <?php if ($verse['key_teaching']): ?><p class="fw-semibold"><?= e($verse['key_teaching']) ?></p><?php endif; ?>
          <?php if ($verse['explanation']): ?><p><?= nl2br(e($verse['explanation'])) ?></p><?php endif; ?>
          <?php if ($verse['philosophical_meaning']): ?><p><?= nl2br(e($verse['philosophical_meaning'])) ?></p><?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($teachings !== []): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-2"><?= e(__t('label.practical_application')) ?></div>
          <?php foreach ($teachings as $teaching): ?>
            <div class="mb-3">
              <a href="<?= e(url('situations')) ?>" class="fw-semibold text-decoration-none"><?= e($teaching['title']) ?></a>
              <p class="text-muted small mb-1"><?= e(str_excerpt($teaching['how_to_apply'], 200)) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($sources !== []): ?>
        <div class="content-block">
          <div class="badge-label text-muted mb-2"><?= e(__t('verse.source_heading')) ?></div>
          <?php foreach ($sources as $source): ?>
            <p class="text-muted small mb-1"><?= e($source['source_title']) ?><?= $source['translator'] ? ' — trans. ' . e($source['translator']) : '' ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="col-lg-4">
      <div class="wisdom-card mb-4">
        <div class="badge-label text-muted mb-2"><?= e(__t('label.reflection')) ?></div>
        <?php if ($teachings !== [] && $teachings[0]['reflection_question']): ?>
          <p class="fst-italic mb-0">"<?= e($teachings[0]['reflection_question']) ?>"</p>
        <?php else: ?>
          <p class="fst-italic mb-0">"<?= e(__t('verse.default_reflection')) ?>"</p>
        <?php endif; ?>
      </div>

      <?php if ($topics !== []): ?>
        <div class="mb-4">
          <h2 class="h6 text-uppercase text-muted"><?= e(__t('verse.topics_heading')) ?></h2>
          <div class="d-flex flex-wrap gap-2">
            <?php foreach ($topics as $topic): ?>
              <a href="<?= e(url('topics/' . $topic['slug'])) ?>" class="btn btn-sm btn-outline-secondary rounded-pill"><?= e($topic['name']) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if ($situations !== []): ?>
        <div class="mb-4">
          <h2 class="h6 text-uppercase text-muted"><?= e(__t('verse.related_situations')) ?></h2>
          <ul class="list-unstyled">
            <?php foreach ($situations as $situation): ?>
              <li><a href="<?= e(url('situations/' . $situation['slug'])) ?>"><?= e($situation['name']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($mantras !== []): ?>
        <div class="mb-4">
          <h2 class="h6 text-uppercase text-muted"><?= e(__t('verse.related_mantras')) ?></h2>
          <ul class="list-unstyled">
            <?php foreach ($mantras as $mantra): ?>
              <li><a href="<?= e(url('mantras/' . $mantra['slug'])) ?>"><?= e($mantra['title']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
