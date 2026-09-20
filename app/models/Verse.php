<?php
declare(strict_types=1);

final class Verse extends Model
{
    protected static string $table = 'verses';

    public static function forChapter(int $chapterId): array
    {
        return self::where(['chapter_id' => $chapterId, 'status' => 'published'], 'verse_number ASC');
    }

    public static function findFull(int $id): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT v.*, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number, c.name AS chapter_name
             FROM verses v
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE v.id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function findByReference(string $gitaSlug, int $chapterNumber, int $verseNumber): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT v.*, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number, c.name AS chapter_name
             FROM verses v
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE g.slug = ? AND c.chapter_number = ? AND v.verse_number = ?
             LIMIT 1'
        );
        $stmt->execute([$gitaSlug, $chapterNumber, $verseNumber]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function topicsFor(int $verseId): array
    {
        $stmt = self::db()->prepare(
            'SELECT t.* FROM topics t JOIN verse_topics vt ON vt.topic_id = t.id WHERE vt.verse_id = ? ORDER BY t.name'
        );
        $stmt->execute([$verseId]);
        return $stmt->fetchAll();
    }

    public static function situationsFor(int $verseId): array
    {
        $stmt = self::db()->prepare(
            'SELECT s.* FROM situations s JOIN verse_situations vs ON vs.situation_id = s.id WHERE vs.verse_id = ? ORDER BY s.name'
        );
        $stmt->execute([$verseId]);
        return $stmt->fetchAll();
    }

    public static function teachingsFor(int $verseId): array
    {
        $stmt = self::db()->prepare(
            'SELECT te.* FROM teachings te JOIN verse_teachings vte ON vte.teaching_id = te.id WHERE vte.verse_id = ? AND te.status = "published" ORDER BY te.title'
        );
        $stmt->execute([$verseId]);
        return $stmt->fetchAll();
    }

    public static function mantrasFor(int $verseId): array
    {
        $stmt = self::db()->prepare(
            'SELECT m.* FROM mantras m
             JOIN mantra_teachings mt ON mt.mantra_id = m.id
             JOIN verse_teachings vte ON vte.teaching_id = mt.teaching_id
             WHERE vte.verse_id = ? AND m.status = "published"
             GROUP BY m.id'
        );
        $stmt->execute([$verseId]);
        return $stmt->fetchAll();
    }

    public static function sourcesFor(int $verseId): array
    {
        $stmt = self::db()->prepare('SELECT * FROM verse_sources WHERE verse_id = ?');
        $stmt->execute([$verseId]);
        return $stmt->fetchAll();
    }

    /** Search across verse text, joined with Gita/chapter so results are linkable. */
    public static function searchFull(string $term, int $limit = 15): array
    {
        $stmt = self::db()->prepare(
            'SELECT v.*, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number
             FROM verses v
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE v.status = "published" AND (
                v.sanskrit_text LIKE ? OR v.transliteration LIKE ? OR
                v.simple_translation_en LIKE ? OR v.key_teaching LIKE ?
             )
             LIMIT ' . (int) $limit
        );
        $like = '%' . $term . '%';
        $stmt->execute([$like, $like, $like, $like]);
        return $stmt->fetchAll();
    }

    /** All published verses joined with their Gita/chapter, for random/featured picks. */
    public static function randomPublished(int $limit = 1): array
    {
        $stmt = self::db()->prepare(
            'SELECT v.*, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number
             FROM verses v JOIN gitas g ON g.id = v.gita_id JOIN chapters c ON c.id = v.chapter_id
             WHERE v.status = "published" ORDER BY RAND() LIMIT ' . (int) $limit
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
