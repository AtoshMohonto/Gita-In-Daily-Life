<?php
declare(strict_types=1);

final class DailyWisdom extends Model
{
    protected static string $table = 'daily_wisdom';

    public static function forDate(string $date): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT dw.*, v.sanskrit_text, v.simple_translation_en, v.chapter_id, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number, v.verse_number
             FROM daily_wisdom dw
             JOIN verses v ON v.id = dw.verse_id
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE dw.wisdom_date = ? AND dw.status = "published"
             LIMIT 1'
        );
        $stmt->execute([$date]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /** Falls back to the most recent published wisdom if nothing is scheduled for today. */
    public static function today(): ?array
    {
        $today = self::forDate(date('Y-m-d'));
        if ($today !== null) {
            return $today;
        }

        $stmt = self::db()->prepare(
            'SELECT dw.*, v.sanskrit_text, v.simple_translation_en, v.chapter_id, g.name AS gita_name, g.slug AS gita_slug, c.chapter_number, v.verse_number
             FROM daily_wisdom dw
             JOIN verses v ON v.id = dw.verse_id
             JOIN gitas g ON g.id = v.gita_id
             JOIN chapters c ON c.id = v.chapter_id
             WHERE dw.status = "published"
             ORDER BY dw.wisdom_date DESC LIMIT 1'
        );
        $stmt->execute();
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
}
