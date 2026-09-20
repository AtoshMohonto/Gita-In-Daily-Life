<?php
declare(strict_types=1);

final class ActivityLog extends Model
{
    protected static string $table = 'activity_logs';

    public static function record(string $action, string $module, ?int $recordId, string $description): void
    {
        self::insert([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'description' => $description,
            'ip_address' => Request::ip(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function recent(int $limit = 10): array
    {
        $stmt = self::db()->prepare(
            'SELECT al.*, u.name AS user_name
             FROM activity_logs al LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC LIMIT ' . (int) $limit
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
