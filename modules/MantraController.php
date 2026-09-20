<?php
declare(strict_types=1);

final class MantraController extends Controller
{
    public function index(): void
    {
        $this->view('mantras/index', [
            'pageTitle' => 'Mantras — ' . APP_NAME,
            'mantras' => Mantra::published(),
        ]);
    }

    public function show(string $slug): void
    {
        $mantra = Mantra::findBySlug($slug);
        if ($mantra === null || $mantra['status'] !== 'published') {
            $this->abort404();
            return;
        }

        $this->view('mantras/show', [
            'pageTitle' => $mantra['title'] . ' — ' . APP_NAME,
            'metaDescription' => $mantra['meaning'],
            'mantra' => $mantra,
            'situations' => Mantra::situationsFor((int) $mantra['id']),
        ]);
    }
}
