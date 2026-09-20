<?php
declare(strict_types=1);

abstract class Controller
{
    /**
     * Renders a content view inside the shared frontend layout.
     * @param array<string,mixed> $data
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = VIEWS_PATH . '/frontend/' . $view . '.php';
        if (!is_file($viewFile)) {
            throw new RuntimeException("View not found: {$view}");
        }

        $pageTitle = $data['pageTitle'] ?? APP_NAME;
        $metaDescription = $data['metaDescription'] ?? Setting::get('default_meta_description', 'Ancient wisdom for modern life.');

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require VIEWS_PATH . '/frontend/layout.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function abort404(): void
    {
        http_response_code(404);
        $this->view('errors/404', ['pageTitle' => 'Page Not Found']);
        exit;
    }
}
