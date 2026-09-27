<div class="container py-5">
  <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= e(url('')) ?>"><?= e(__t('breadcrumb.home')) ?></a></li><li class="breadcrumb-item active"><?= e(__t('breadcrumb.topics')) ?></li></ol></nav>
  <h1 class="h2 font-serif mb-4"><?= e(__t('topics.page_title')) ?></h1>
  <div class="row g-3">
    <?php foreach ($topics as $topic): ?>
      <div class="col-md-4">
        <a href="<?= e(url('topics/' . $topic['slug'])) ?>" class="card-gidl p-4 d-block text-decoration-none text-reset h-100">
          <h2 class="h5 font-serif"><?= e($topic['name']) ?></h2>
          <p class="text-muted small mb-0"><?= e(str_excerpt($topic['description'], 100)) ?></p>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>
