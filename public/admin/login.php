<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (Auth::check()) {
    header('Location: ' . url('admin/dashboard.php'));
    exit;
}

$error = null;

if (Request::isPost()) {
    if (!Csrf::verifyRequest()) {
        $error = 'Security check failed. Please try again.';
    } else {
        $identifier = Request::post('identifier', '');
        $password = (string) Request::post('password', '');
        $result = Auth::attempt($identifier, $password);
        if ($result['success']) {
            header('Location: ' . url('admin/dashboard.php'));
            exit;
        }
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — <?= e(APP_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@600;700&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background: #faf6ef; font-family: 'Noto Sans', system-ui, sans-serif; min-height: 100vh; display: flex; align-items: center; }
  .login-card { max-width: 380px; width: 100%; margin: auto; background: #fff; border: 1px solid #e8dfd0; border-radius: .75rem; padding: 2.5rem; }
  .brand { font-family: 'Noto Serif', serif; font-weight: 700; color: #5e2c2c; }
  .btn-primary { background-color: #7a3b3b; border-color: #7a3b3b; }
  .btn-primary:hover { background-color: #5e2c2c; border-color: #5e2c2c; }
</style>
</head>
<body>
  <div class="login-card">
    <div class="text-center mb-4">
      <div class="brand fs-4">🕉 <?= e(APP_NAME) ?></div>
      <div class="text-muted small">Admin Sign In</div>
    </div>
    <?php if ($error): ?>
      <div class="alert alert-danger small"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <?= Csrf::field() ?>
      <div class="mb-3">
        <label class="form-label small">Email or Username</label>
        <input type="text" name="identifier" class="form-control" required autofocus value="<?= old('identifier') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label small">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Sign In</button>
    </form>
    <div class="text-center mt-3">
      <a href="<?= e(url('')) ?>" class="small text-muted">&larr; Back to site</a>
    </div>
  </div>
</body>
</html>
