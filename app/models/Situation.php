<?php
declare(strict_types=1);

final class Situation extends Model
{
    protected static string $table = 'situations';

    public static function published(): array
    {
        return self::where(['status' => 'published'], 'name ASC');
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    public static function versesFor(int $situationId): array
    {
        $stmt = self::db()->prepare(
            'SELECT v.*, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number
             FROM verses v
             JOIN verse_situations vs ON vs.verse_id = v.id
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE vs.situation_id = ? AND v.status = "published"'
        );
        $stmt->execute([$situationId]);
        return $stmt->fetchAll();
    }

    public static function teachingsFor(int $situationId): array
    {
        $stmt = self::db()->prepare(
            'SELECT te.* FROM teachings te
             JOIN teaching_situations ts ON ts.teaching_id = te.id
             WHERE ts.situation_id = ? AND te.status = "published"'
        );
        $stmt->execute([$situationId]);
        return $stmt->fetchAll();
    }

    public static function mantrasFor(int $situationId): array
    {
        $stmt = self::db()->prepare(
            'SELECT m.* FROM mantras m
             JOIN mantra_situations ms ON ms.mantra_id = m.id
             WHERE ms.situation_id = ? AND m.status = "published"'
        );
        $stmt->execute([$situationId]);
        return $stmt->fetchAll();
    }
}
