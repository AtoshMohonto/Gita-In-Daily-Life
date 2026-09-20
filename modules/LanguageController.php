<?php
declare(strict_types=1);

final class LanguageController extends Controller
{
    public function switch(string $code): void
    {
        if (in_array($code, ['en', 'bn'], true)) {
            Session::set('lang', $code);
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? url('');
        // Only ever redirect back within this app's own base path.
        if (!str_starts_with($referer, APP_BASE_PATH)) {
            $referer = url('');
        }
        $this->redirect(str_replace(APP_BASE_PATH, '', $referer));
    }
}
