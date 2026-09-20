<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= e(url('')) ?>">Home</a></li><li class="breadcrumb-item active">Today's Wisdom</li></ol></nav>
  <h1 class="h2 font-serif mb-4 text-center">Today's Wisdom</h1>

  <?php if ($wisdom): ?>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <?php
          $card = [
              'quote' => $wisdom['simple_translation_en'],
              'source' => $wisdom['gita_name'] . ' • Chapter ' . $wisdom['chapter_number'] . ' • Verse ' . $wisdom['verse_number'],
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
    <p class="text-muted text-center">No wisdom has been scheduled yet — add one from the admin panel.</p>
  <?php endif; ?>
</div>
