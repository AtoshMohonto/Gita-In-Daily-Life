<?php
declare(strict_types=1);

final class ErrorController extends Controller
{
    public function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', ['pageTitle' => 'Page Not Found']);
    }

    public function forbidden(): void
    {
        http_response_code(403);
        $this->view('errors/403', ['pageTitle' => 'Access Denied']);
    }

    public function serverError(): void
    {
        http_response_code(500);
        $this->view('errors/500', ['pageTitle' => 'Something Went Wrong']);
    }
}
