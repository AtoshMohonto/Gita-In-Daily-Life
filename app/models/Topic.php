<?php
declare(strict_types=1);

final class Topic extends Model
{
    protected static string $table = 'topics';

    public static function published(): array
    {
        return self::where(['status' => 'published'], 'name ASC');
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    /** Verses tagged with this topic, across every Gita — powers "compare teachings". */
    public static function versesFor(int $topicId): array
    {
        $stmt = self::db()->prepare(
            'SELECT v.*, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number
             FROM verses v
             JOIN verse_topics vt ON vt.verse_id = v.id
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE vt.topic_id = ? AND v.status = "published"
             ORDER BY g.name, c.chapter_number, v.verse_number'
        );
        $stmt->execute([$topicId]);
        return $stmt->fetchAll();
    }

    public static function teachingsFor(int $topicId): array
    {
        $stmt = self::db()->prepare(
            'SELECT te.* FROM teachings te
             JOIN teaching_topics tt ON tt.teaching_id = te.id
             WHERE tt.topic_id = ? AND te.status = "published"'
        );
        $stmt->execute([$topicId]);
        return $stmt->fetchAll();
    }
}
