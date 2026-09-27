<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= e(__t('breadcrumb.home')) ?></a></li><li class="breadcrumb-item active"><?= e(__t('breadcrumb.daily_wisdom')) ?></li></ol></nav>
  <h1 class="h2 font-serif mb-4 text-center"><?= e(__t('home.daily_wisdom_title')) ?></h1>

  <?php if ($wisdom): ?>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <?php
          $card = [
              'quote' => verse_text($wisdom),
              'source' => $wisdom['gita_name'] . ' • ' . __t('chapter.label') . ' ' . $wisdom['chapter_number'] . ' • ' . __t('chapter.verse_label') . ' ' . $wisdom['verse_number'],
              'key_idea' => $wisdom['title'],
              'apply' => $wisdom['practical_action'],
              'link' => url('verses/' . $wisdom['gita_slug'] . '/' . $wisdom['chapter_number'] . '/' . $wisdom['verse_number']),
          ];
          include VIEWS_PATH . '/frontend/partials/wisdom-card.php';
        ?>
        <?php if ($wisdom['reflection_question']): ?>
          <div class="wisdom-card mt-4">
            <div class="badge-label text-muted mb-2"><?= e(__t('label.reflection')) ?></div>
            <p class="fst-italic mb-0">"<?= e($wisdom['reflection_question']) ?>"</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <p class="text-muted text-center"><?= e(__t('daily_wisdom.none_yet')) ?></p>
  <?php endif; ?>
</div>
