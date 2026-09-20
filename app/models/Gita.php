<?php
declare(strict_types=1);

final class Gita extends Model
{
    protected static string $table = 'gitas';

    public static function published(?int $limit = null): array
    {
        return self::where(['status' => 'published'], 'created_at DESC', $limit);
    }

    public static function featured(int $limit = 6): array
    {
        return self::where(['status' => 'published', 'featured' => 1], 'created_at DESC', $limit);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    public static function chapterCount(int $gitaId): int
    {
        return Chapter::count(['gita_id' => $gitaId]);
    }

    public static function verseCount(int $gitaId): int
    {
        return Verse::count(['gita_id' => $gitaId, 'status' => 'published']);
    }
}
