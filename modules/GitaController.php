<?php
declare(strict_types=1);

final class GitaController extends Controller
{
    public function index(): void
    {
        $q = trim((string) Request::query('q', ''));
        $gitas = $q !== ''
            ? Gita::search(['name', 'short_description'], $q, ['status' => 'published'], 'name ASC')
            : Gita::published();

        $this->view('gitas/index', [
            'pageTitle' => 'Explore the Gitas — ' . APP_NAME,
            'gitas' => $gitas,
            'q' => $q,
        ]);
    }

    public function show(string $slug): void
    {
        $gita = Gita::findBySlug($slug);
        if ($gita === null || $gita['status'] !== 'published') {
            $this->abort404();
            return;
        }

        $chapters = Chapter::forGita((int) $gita['id']);

        $this->view('gitas/show', [
            'pageTitle' => $gita['name'] . ' — ' . APP_NAME,
            'metaDescription' => $gita['meta_description'] ?: $gita['short_description'],
            'gita' => $gita,
            'chapters' => $chapters,
        ]);
    }

    public function chapter(string $slug, string $number): void
    {
        $gita = Gita::findBySlug($slug);
        if ($gita === null || $gita['status'] !== 'published') {
            $this->abort404();
            return;
        }

        $chapter = Chapter::findByGitaAndNumber((int) $gita['id'], (int) $number);
        if ($chapter === null) {
            $this->abort404();
            return;
        }

        $verses = Verse::forChapter((int) $chapter['id']);

        $this->view('gitas/chapter', [
            'pageTitle' => 'Chapter ' . $chapter['chapter_number'] . ': ' . $chapter['name'] . ' — ' . $gita['name'],
            'gita' => $gita,
            'chapter' => $chapter,
            'verses' => $verses,
        ]);
    }
}
