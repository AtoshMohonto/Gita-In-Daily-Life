<?php
declare(strict_types=1);

final class SituationController extends Controller
{
    public function index(): void
    {
        $this->view('situations/index', [
            'pageTitle' => 'What Are You Facing Today? — ' . APP_NAME,
            'situations' => Situation::published(),
        ]);
    }

    public function show(string $slug): void
    {
        $situation = Situation::findBySlug($slug);
        if ($situation === null || $situation['status'] !== 'published') {
            $this->abort404();
            return;
        }

        $situationId = (int) $situation['id'];

        $this->view('situations/show', [
            'pageTitle' => $situation['name'] . ' — ' . APP_NAME,
            'metaDescription' => $situation['description'],
            'situation' => $situation,
            'verses' => Situation::versesFor($situationId),
            'teachings' => Situation::teachingsFor($situationId),
            'mantras' => Situation::mantrasFor($situationId),
        ]);
    }
}
