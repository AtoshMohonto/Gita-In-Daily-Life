<?php
declare(strict_types=1);

final class Teaching extends Model
{
    protected static string $table = 'teachings';

    public static function published(?int $limit = null): array
    {
        return self::where(['status' => 'published'], 'created_at DESC', $limit);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    public static function versesFor(int $teachingId): array
    {
        $stmt = self::db()->prepare(
            'SELECT v.*, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number
             FROM verses v
             JOIN verse_teachings vt ON vt.verse_id = v.id
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE vt.teaching_id = ?'
        );
        $stmt->execute([$teachingId]);
        return $stmt->fetchAll();
    }

    public static function mantrasFor(int $teachingId): array
    {
        $stmt = self::db()->prepare(
            'SELECT m.* FROM mantras m JOIN mantra_teachings mt ON mt.mantra_id = m.id WHERE mt.teaching_id = ?'
        );
        $stmt->execute([$teachingId]);
        return $stmt->fetchAll();
    }
}
