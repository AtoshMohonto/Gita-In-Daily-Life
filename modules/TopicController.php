<?php
declare(strict_types=1);

final class TopicController extends Controller
{
    public function index(): void
    {
        $this->view('topics/index', [
            'pageTitle' => 'Topics — ' . APP_NAME,
            'topics' => Topic::published(),
        ]);
    }

    public function show(string $slug): void
    {
        $topic = Topic::findBySlug($slug);
        if ($topic === null || $topic['status'] !== 'published') {
            $this->abort404();
            return;
        }

        $this->view('topics/show', [
            'pageTitle' => $topic['name'] . ' — ' . APP_NAME,
            'metaDescription' => $topic['description'],
            'topic' => $topic,
            'verses' => Topic::versesFor((int) $topic['id']),
            'teachings' => Topic::teachingsFor((int) $topic['id']),
        ]);
    }
}
