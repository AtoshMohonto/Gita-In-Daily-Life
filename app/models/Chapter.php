<?php
declare(strict_types=1);

final class Chapter extends Model
{
    protected static string $table = 'chapters';

    public static function forGita(int $gitaId): array
    {
        return self::where(['gita_id' => $gitaId], 'chapter_number ASC');
    }

    public static function findByGitaAndNumber(int $gitaId, int $number): ?array
    {
        $rows = self::where(['gita_id' => $gitaId, 'chapter_number' => $number], '', 1);
        return $rows[0] ?? null;
    }

    public static function verseCount(int $chapterId): int
    {
        return Verse::count(['chapter_id' => $chapterId, 'status' => 'published']);
    }
}
