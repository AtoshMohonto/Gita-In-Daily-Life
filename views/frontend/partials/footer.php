<footer class="site-footer py-5 mt-5">
  <div class="container">
    <div class="row">
      <div class="col-md-6 mb-3 mb-md-0">
        <div class="font-serif fw-bold mb-2" style="color:var(--gidl-primary-dark);">🕉 <?= e(APP_NAME) ?></div>
        <p class="small mb-0"><?= e(Setting::get('site_tagline', 'Ancient wisdom for modern life.')) ?></p>
      </div>
      <div class="col-md-6">
        <p class="small mb-0"><?= e(__t('footer.disclaimer')) ?></p>
      </div>
    </div>
    <hr class="my-4">
    <p class="small mb-0">&copy; <?= date('Y') ?> <?= e(APP_NAME) ?>. <?= e(__t('footer.rights')) ?></p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
