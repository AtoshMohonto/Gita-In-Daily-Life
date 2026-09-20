<?php
declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole(['super_admin', 'admin']);

$fields = [
    'site_name' => ['label' => 'Site Name', 'group' => 'general'],
    'site_tagline' => ['label' => 'Tagline', 'group' => 'general'],
    'contact_email' => ['label' => 'Contact Email', 'group' => 'general'],
    'default_meta_description' => ['label' => 'Default Meta Description', 'group' => 'seo'],
    'pagination_size' => ['label' => 'Pagination Size', 'group' => 'content'],
    'default_language' => ['label' => 'Default Language (en / bn)', 'group' => 'content'],
    'social_facebook' => ['label' => 'Facebook URL', 'group' => 'social'],
    'social_youtube' => ['label' => 'YouTube URL', 'group' => 'social'],
    'social_instagram' => ['label' => 'Instagram URL', 'group' => 'social'],
];

if (Request::isPost()) {
    if (!Csrf::verifyRequest()) {
        flash('error', 'Security check failed. Please try again.');
    } else {
        foreach ($fields as $key => $meta) {
            Setting::set($key, (string) Request::post($key, ''), $meta['group']);
        }
        activity_log('update', 'settings', null, 'Updated site settings');
        flash('success', 'Settings saved.');
    }
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$pageTitle = 'Settings';
$activeModule = 'settings';
require __DIR__ . '/../../includes/header.php';
?>
<h1 class="h3 mb-4">Settings</h1>
<?php $success = flash('success'); $error = flash('error'); ?>
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

<form method="post" class="card border-0 shadow-sm p-4" style="max-width:640px;">
  <?= Csrf::field() ?>
  <?php foreach ($fields as $key => $meta): ?>
    <div class="mb-3">
      <label class="form-label" for="f_<?= e($key) ?>"><?= e($meta['label']) ?></label>
      <input type="text" class="form-control" id="f_<?= e($key) ?>" name="<?= e($key) ?>" value="<?= e(Setting::get($key, '')) ?>">
    </div>
  <?php endforeach; ?>
  <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
