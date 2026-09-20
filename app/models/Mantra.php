<?php
declare(strict_types=1);

final class Mantra extends Model
{
    protected static string $table = 'mantras';

    public static function published(?int $limit = null): array
    {
        return self::where(['status' => 'published'], 'title ASC', $limit);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    public static function situationsFor(int $mantraId): array
    {
        $stmt = self::db()->prepare(
            'SELECT s.* FROM situations s JOIN mantra_situations ms ON ms.situation_id = s.id WHERE ms.mantra_id = ?'
        );
        $stmt->execute([$mantraId]);
        return $stmt->fetchAll();
    }

    public static function forSituation(int $situationId): array
    {
        return Situation::mantrasFor($situationId);
    }
}
