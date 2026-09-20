<?php
declare(strict_types=1);

final class VerseController extends Controller
{
    public function show(string $gitaSlug, string $chapterNumber, string $verseNumber): void
    {
        $verse = Verse::findByReference($gitaSlug, (int) $chapterNumber, (int) $verseNumber);
        if ($verse === null || $verse['status'] !== 'published') {
            $this->abort404();
            return;
        }

        $verseId = (int) $verse['id'];

        $this->view('verses/show', [
            'pageTitle' => $verse['gita_name'] . ' ' . $verse['chapter_number'] . '.' . $verse['verse_number'] . ' — ' . APP_NAME,
            'metaDescription' => str_excerpt((string) $verse['simple_translation_en'], 160),
            'verse' => $verse,
            'topics' => Verse::topicsFor($verseId),
            'situations' => Verse::situationsFor($verseId),
            'teachings' => Verse::teachingsFor($verseId),
            'mantras' => Verse::mantrasFor($verseId),
            'sources' => Verse::sourcesFor($verseId),
        ]);
    }
}
