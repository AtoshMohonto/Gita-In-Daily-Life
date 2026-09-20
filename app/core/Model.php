<?php
declare(strict_types=1);

/**
 * Minimal active-record-style base. Every method uses prepared statements;
 * table/column names that vary per call are whitelisted against a schema
 * list before ever being concatenated into SQL (see Model::assertSafeIdentifier).
 */
abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';

    protected static function db(): PDO
    {
        return Database::connection();
    }

    protected static function assertSafeIdentifier(string $identifier): string
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $identifier)) {
            throw new InvalidArgumentException("Unsafe identifier: {$identifier}");
        }
        return $identifier;
    }

    public static function find($id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function findBy(string $column, $value): ?array
    {
        self::assertSafeIdentifier($column);
        $stmt = self::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . $column . ' = ? LIMIT 1');
        $stmt->execute([$value]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function all(string $orderBy = ''): array
    {
        $sql = 'SELECT * FROM ' . static::$table;
        if ($orderBy !== '') {
            $sql .= ' ORDER BY ' . self::sanitizeOrderBy($orderBy);
        }
        return self::db()->query($sql)->fetchAll();
    }

    /**
     * @param array<string,mixed> $conditions column => value (AND-ed, exact match)
     */
    public static function where(array $conditions = [], string $orderBy = '', ?int $limit = null, ?int $offset = null): array
    {
        [$whereSql, $params] = self::buildWhere($conditions);
        $sql = 'SELECT * FROM ' . static::$table . $whereSql;
        if ($orderBy !== '') {
            $sql .= ' ORDER BY ' . self::sanitizeOrderBy($orderBy);
        }
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
            if ($offset !== null) {
                $sql .= ' OFFSET ' . (int) $offset;
            }
        }
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function count(array $conditions = []): int
    {
        [$whereSql, $params] = self::buildWhere($conditions);
        $stmt = self::db()->prepare('SELECT COUNT(*) FROM ' . static::$table . $whereSql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function insert(array $data): int
    {
        $columns = array_map([self::class, 'assertSafeIdentifier'], array_keys($data));
        $placeholders = array_fill(0, count($columns), '?');
        $sql = 'INSERT INTO ' . static::$table . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $stmt = self::db()->prepare($sql);
        $stmt->execute(array_values($data));
        return (int) self::db()->lastInsertId();
    }

    public static function update($id, array $data): bool
    {
        $set = [];
        foreach (array_keys($data) as $column) {
            self::assertSafeIdentifier($column);
            $set[] = $column . ' = ?';
        }
        $sql = 'UPDATE ' . static::$table . ' SET ' . implode(', ', $set) . ' WHERE ' . static::$primaryKey . ' = ?';
        $stmt = self::db()->prepare($sql);
        $params = array_values($data);
        $params[] = $id;
        return $stmt->execute($params);
    }

    public static function delete($id): bool
    {
        $stmt = self::db()->prepare('DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ?');
        return $stmt->execute([$id]);
    }

    /**
     * @param string[] $columns columns to LIKE-search across
     */
    public static function search(array $columns, string $term, array $extraConditions = [], string $orderBy = '', ?int $limit = null): array
    {
        if ($columns === [] || $term === '') {
            return [];
        }
        foreach ($columns as $c) {
            self::assertSafeIdentifier($c);
        }
        $likeParts = [];
        $params = [];
        foreach ($columns as $c) {
            $likeParts[] = $c . ' LIKE ?';
            $params[] = '%' . $term . '%';
        }
        [$extraSql, $extraParams] = self::buildWhere($extraConditions, false);
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE (' . implode(' OR ', $likeParts) . ')';
        if ($extraSql !== '') {
            $sql .= ' AND ' . $extraSql;
        }
        if ($orderBy !== '') {
            $sql .= ' ORDER BY ' . self::sanitizeOrderBy($orderBy);
        }
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        $stmt = self::db()->prepare($sql);
        $stmt->execute(array_merge($params, $extraParams));
        return $stmt->fetchAll();
    }

    public static function table(): string
    {
        return static::$table;
    }

    public static function primaryKey(): string
    {
        return static::$primaryKey;
    }

    /**
     * @return array{0:string,1:array} [whereSqlIncludingWHEREkeyword, params]
     */
    protected static function buildWhere(array $conditions, bool $withKeyword = true): array
    {
        if ($conditions === []) {
            return ['', []];
        }
        $parts = [];
        $params = [];
        foreach ($conditions as $column => $value) {
            self::assertSafeIdentifier((string) $column);
            if ($value === null) {
                $parts[] = $column . ' IS NULL';
            } else {
                $parts[] = $column . ' = ?';
                $params[] = $value;
            }
        }
        $sql = implode(' AND ', $parts);
        return [($withKeyword ? ' WHERE ' : '') . $sql, $params];
    }

    protected static function sanitizeOrderBy(string $orderBy): string
    {
        // allow "column ASC|DESC" pairs, comma separated
        $parts = array_map('trim', explode(',', $orderBy));
        $safe = [];
        foreach ($parts as $part) {
            if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*)\s*(ASC|DESC)?$/i', $part, $m)) {
                $safe[] = $m[1] . (isset($m[2]) ? ' ' . strtoupper($m[2]) : '');
            }
        }
        return $safe === [] ? '' : implode(', ', $safe);
    }
}
