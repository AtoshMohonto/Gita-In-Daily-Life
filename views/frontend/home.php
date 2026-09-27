<section class="hero-gidl py-5">
  <div class="container py-4 text-center">
    <h1 class="display-5 fw-bold mb-2"><?= e(__t('home.hero_title')) ?></h1>
    <p class="fs-4 font-serif mb-2" style="color:var(--gidl-primary-dark);"><?= e(__t('home.hero_subtitle')) ?></p>
    <p class="text-muted mx-auto mb-4" style="max-width:640px;"><?= e(__t('home.hero_supporting')) ?></p>
    <div class="d-flex flex-wrap justify-content-center gap-2">
      <a href="<?= e(url('gitas')) ?>" class="btn btn-primary"><?= e(__t('home.cta_explore')) ?></a>
      <a href="<?= e(url('situations')) ?>" class="btn btn-outline-primary"><?= e(__t('home.cta_situation')) ?></a>
      <a href="<?= e(url('daily-wisdom')) ?>" class="btn btn-outline-secondary"><?= e(__t('home.cta_today')) ?></a>
    </div>
  </div>
</section>

<section class="container py-5">
  <h2 class="h3 mb-4"><?= e(__t('home.featured_gitas')) ?></h2>
  <div class="row g-4">
    <?php foreach ($featuredGitas as $gita): ?>
      <div class="col-md-4">
        <a href="<?= e(url('gitas/' . $gita['slug'])) ?>" class="text-decoration-none text-reset">
          <div class="card-gidl p-4 h-100">
            <h3 class="h5 font-serif"><?= e($gita['name']) ?></h3>
            <p class="text-muted small mb-3"><?= e(str_excerpt($gita['short_description'], 110)) ?></p>
            <div class="small text-muted mb-2">
              <?= (int) Gita::chapterCount((int) $gita['id']) ?> <?= e(__t('gitas.chapters_suffix')) ?> &middot;
              <?= (int) Gita::verseCount((int) $gita['id']) ?> <?= e(__t('gitas.verses_suffix')) ?>
            </div>
            <span class="btn btn-sm btn-outline-primary"><?= e(__t('action.view_chapters')) ?></span>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
    <?php if ($featuredGitas === []): ?>
      <p class="text-muted"><?= e(__t('gitas.none_yet')) ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="py-5" style="background:var(--gidl-surface); border-top:1px solid var(--gidl-border); border-bottom:1px solid var(--gidl-border);">
  <div class="container">
    <h2 class="h3 mb-4"><?= e(__t('home.situations_title')) ?></h2>
    <div class="row g-3">
      <?php foreach ($situations as $situation): ?>
        <div class="col-6 col-md-3">
          <a class="situation-tile" href="<?= e(url('situations/' . $situation['slug'])) ?>">
            <i class="bi bi-<?= e($situation['icon'] ?: 'compass') ?> fs-3 mb-2 d-block" style="color:var(--gidl-accent);"></i>
            <div class="fw-semibold"><?= e($situation['name']) ?></div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($dailyWisdom): ?>
<section class="container py-5">
  <h2 class="h3 mb-4"><?= e(__t('home.daily_wisdom_title')) ?></h2>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <?php
        $card = [
            'quote' => verse_text($dailyWisdom),
            'source' => $dailyWisdom['gita_name'] . ' • ' . __t('chapter.label') . ' ' . $dailyWisdom['chapter_number'] . ' • ' . __t('chapter.verse_label') . ' ' . $dailyWisdom['verse_number'],
            'key_idea' => $dailyWisdom['title'],
            'apply' => $dailyWisdom['practical_action'],
            'link' => url('verses/' . $dailyWisdom['gita_slug'] . '/' . $dailyWisdom['chapter_number'] . '/' . $dailyWisdom['verse_number']),
        ];
        include VIEWS_PATH . '/frontend/partials/wisdom-card.php';
      ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="container pb-5">
  <h2 class="h3 mb-4"><?= e(__t('nav.topics')) ?></h2>
  <div class="d-flex flex-wrap gap-2">
    <?php foreach ($topics as $topic): ?>
      <a href="<?= e(url('topics/' . $topic['slug'])) ?>" class="btn btn-sm btn-outline-secondary rounded-pill"><?= e($topic['name']) ?></a>
    <?php endforeach; ?>
  </div>
</section>
