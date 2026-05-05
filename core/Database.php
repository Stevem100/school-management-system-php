<?php

declare(strict_types=1);

namespace Core;

/**
 * MySQL PDO Database Client
 *
 * Communicates with a MySQL database using PHP PDO.
 * Supports full CRUD operations with filtering, ordering, pagination,
 * and column selection. Handles snake_case <-> camelCase conversion.
 *
 * Uses prepared statements exclusively to prevent SQL injection.
 */
class Database
{
    /** @var \PDO PDO connection instance */
    private \PDO $pdo;

    /**
     * Create a new Database instance.
     *
     * @param string $host     Database host
     * @param string $port     Database port
     * @param string $database Database name
     * @param string $username Database username
     * @param string $password Database password
     * @param string $charset  Connection charset (default: utf8mb4)
     */
    public function __construct(
        string $host,
        string $port,
        string $database,
        string $username,
        string $password,
        string $charset = 'utf8mb4'
    ) {
        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";

        try {
            $this->pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (\PDOException $e) {
            throw new \RuntimeException(
                'Database connection failed: ' . $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    /**
     * Execute a query against the MySQL database.
     *
     * Main method supporting GET (SELECT), POST (INSERT), PATCH (UPDATE),
     * and DELETE operations through a unified interface.
     *
     * @param string      $table   Table name in the database
     * @param string      $method  HTTP-style method: GET, POST, PATCH, DELETE
     * @param array|null  $data    Data payload for POST/PATCH requests
     * @param array       $filters Associative array of filters: [column => [operator => value]]
     * @param string|null $order   Ordering string, e.g. "created_at.desc" or "name.asc"
     * @param int|null    $limit   Maximum number of records to return
     * @param int|null    $offset  Number of records to skip
     * @param string|null $select  Comma-separated list of columns to select
     * @return array              Response data as associative array
     * @throws \RuntimeException  On PDO or SQL errors
     */
    public function query(
        string $table,
        string $method = 'GET',
        ?array $data = null,
        array $filters = [],
        ?string $order = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $select = null
    ): array {
        $method = strtoupper($method);
        $table = $this->toSnakeCase($table);

        switch ($method) {
            case 'GET':
                return $this->executeSelect($table, $filters, $order, $limit, $offset, $select);

            case 'POST':
                return $this->executeInsert($table, $data ?? []);

            case 'PATCH':
                return $this->executeUpdate($table, $data ?? [], $filters);

            case 'DELETE':
                return $this->executeDelete($table, $filters);

            default:
                throw new \RuntimeException("Unsupported query method: {$method}");
        }
    }

    /**
     * Fetch all records matching filters.
     *
     * @param string      $table   Table name
     * @param array       $filters Filter conditions
     * @param string|null $order   Ordering
     * @param int|null    $limit   Max records
     * @param int|null    $offset  Records to skip
     * @param string|null $select  Columns to select
     * @return array               Array of records
     */
    public function select(
        string $table,
        array $filters = [],
        ?string $order = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $select = null
    ): array {
        return $this->query($table, 'GET', null, $filters, $order, $limit, $offset, $select);
    }

    /**
     * Fetch a single record matching filters.
     *
     * @param string      $table   Table name
     * @param array       $filters Filter conditions
     * @param string|null $select  Columns to select
     * @return array|null          Single record or null
     */
    public function single(string $table, array $filters = [], ?string $select = null): ?array
    {
        $results = $this->query($table, 'GET', null, $filters, null, 1, 0, $select);
        return $results[0] ?? null;
    }

    /**
     * Find a record by its primary key (id column).
     *
     * @param string $table    Table name
     * @param mixed  $id       Primary key value
     * @param string $idColumn Name of the primary key column (default: id)
     * @return array|null      Record or null
     */
    public function find(string $table, $id, string $idColumn = 'id'): ?array
    {
        return $this->single($table, [$idColumn => ['eq' => $id]]);
    }

    /**
     * Insert a new record.
     *
     * @param string $table Table name
     * @param array  $data  Record data
     * @return array        Inserted record (fetched back from the DB)
     */
    public function insert(string $table, array $data): array
    {
        $results = $this->query($table, 'POST', $data);
        return is_array($results) && isset($results[0]) ? $results[0] : $results;
    }

    /**
     * Update records matching filters.
     *
     * @param string $table   Table name
     * @param array  $data    Data to update
     * @param array  $filters Filter conditions to identify records
     * @return array          Updated records
     */
    public function update(string $table, array $data, array $filters = []): array
    {
        return $this->query($table, 'PATCH', $data, $filters);
    }

    /**
     * Update a single record by primary key.
     *
     * @param string $table Table name
     * @param mixed  $id    Primary key value
     * @param array  $data  Data to update
     * @return array        Updated record
     */
    public function updateById(string $table, $id, array $data): array
    {
        return $this->update($table, $data, ['id' => ['eq' => $id]]);
    }

    /**
     * Delete records matching filters.
     *
     * @param string $table   Table name
     * @param array  $filters Filter conditions
     * @return array          Deleted records
     */
    public function delete(string $table, array $filters = []): array
    {
        return $this->query($table, 'DELETE', null, $filters);
    }

    /**
     * Delete a single record by primary key.
     *
     * @param string $table Table name
     * @param mixed  $id    Primary key value
     * @return array        Deleted record
     */
    public function deleteById(string $table, $id): array
    {
        return $this->delete($table, ['id' => ['eq' => $id]]);
    }

    /**
     * Count records matching filters.
     *
     * @param string $table   Table name
     * @param array  $filters Filter conditions
     * @return int            Record count
     */
    public function count(string $table, array $filters = []): int
    {
        $table = $this->toSnakeCase($table);
        $sql = "SELECT COUNT(*) AS total FROM `{$table}`";
        $params = [];
        $values = [];

        [$whereClause, $params, $values] = $this->buildWhereClause($filters, $params, $values);

        if ($whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        }

        $stmt = $this->executeStatement($sql, $params, $values);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Execute a raw SQL query with optional bound parameters.
     *
     * @param string $sql    Raw SQL query string
     * @param array  $params Array of values to bind (? placeholders)
     * @return array         Array of result rows
     * @throws \RuntimeException On PDO errors
     */
    public function raw(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $i => $value) {
            $paramIndex = $i + 1;
            $stmt->bindValue($paramIndex, $value, \PDO::PARAM_STR);
        }

        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->arrayKeysToCamelCase($results);
    }

    /**
     * Return the last auto-increment ID inserted.
     *
     * @return string Last insert ID
     */
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Convert a snake_case string to camelCase.
     *
     * @param string $str snake_case string
     * @return string    camelCase string
     */
    public function toCamelCase(string $str): string
    {
        return lcfirst(str_replace('_', '', ucwords($str, '_')));
    }

    /**
     * Convert a camelCase string to snake_case.
     *
     * @param string $str camelCase string
     * @return string    snake_case string
     */
    public function toSnakeCase(string $str): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
    }

    // ---------------------------------------------------------------------------
    // Private execution helpers
    // ---------------------------------------------------------------------------

    /**
     * Execute a SELECT query.
     *
     * @param string      $table   Table name (already snake_case)
     * @param array       $filters Filter conditions
     * @param string|null $order   Ordering
     * @param int|null    $limit   Limit
     * @param int|null    $offset  Offset
     * @param string|null $select  Columns
     * @return array               Array of result rows
     */
    private function executeSelect(
        string $table,
        array $filters,
        ?string $order,
        ?int $limit,
        ?int $offset,
        ?string $select
    ): array {
        // Build SELECT columns
        if ($select !== null && $select !== '') {
            // Convert camelCase column names to snake_case
            $columns = array_map([$this, 'toSnakeCase'], explode(',', $select));
            // Filter out any Supabase-style embedded join references like "roles(name,id)"
            $safeColumns = [];
            foreach ($columns as $col) {
                $col = trim($col);
                if ($col !== '' && strpos($col, '(') === false) {
                    $safeColumns[] = '`' . $col . '`';
                }
            }
            $selectClause = !empty($safeColumns) ? implode(', ', $safeColumns) : '*';
        } else {
            $selectClause = '*';
        }

        $sql = "SELECT {$selectClause} FROM `{$table}`";
        $params = [];
        $values = [];

        // Build WHERE from filters
        [$whereClause, $params, $values] = $this->buildWhereClause($filters, $params, $values);
        if ($whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        }

        // Build ORDER BY
        if ($order !== null && $order !== '') {
            $sql .= $this->buildOrderByClause($order);
        }

        // Build LIMIT / OFFSET
        if ($limit !== null) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        if ($offset !== null) {
            $sql .= ' OFFSET ' . (int) $offset;
        }

        $stmt = $this->executeStatement($sql, $params, $values);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $this->arrayKeysToCamelCase($results);
    }

    /**
     * Execute an INSERT query and return the new record.
     *
     * @param string $table Table name (already snake_case)
     * @param array  $data  Data to insert
     * @return array        Array containing the inserted record
     */
    private function executeInsert(string $table, array $data): array
    {
        $snakeData = $this->arrayKeysToSnakeCase($data);

        if (empty($snakeData)) {
            throw new \RuntimeException('Insert data cannot be empty.');
        }

        $columns = array_map(fn ($col) => '`' . $col . '`', array_keys($snakeData));
        $placeholders = array_fill(0, count($snakeData), '?');

        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $values = array_values($snakeData);
        $stmt = $this->executeStatement($sql, [], $values);

        $lastId = $this->pdo->lastInsertId();

        // Fetch the inserted record back
        if ($lastId) {
            $fetched = $this->single($this->toCamelCase($table), ['id' => ['eq' => $lastId]]);
            return $fetched !== null ? [$fetched] : [];
        }

        // Fallback: return a minimal representation
        $result = array_merge(['id' => $lastId], $snakeData);
        return [$this->arrayKeysToCamelCase($result)];
    }

    /**
     * Execute an UPDATE query and return the updated records.
     *
     * @param string $table   Table name (already snake_case)
     * @param array  $data    Data to update
     * @param array  $filters Filter conditions
     * @return array          Array of updated records
     */
    private function executeUpdate(string $table, array $data, array $filters): array
    {
        $snakeData = $this->arrayKeysToSnakeCase($data);

        if (empty($snakeData)) {
            throw new \RuntimeException('Update data cannot be empty.');
        }

        $setParts = [];
        $values = [];

        foreach (array_keys($snakeData) as $col) {
            $setParts[] = '`' . $col . '` = ?';
            $values[] = $snakeData[$col];
        }

        $sql = sprintf(
            'UPDATE `%s` SET %s',
            $table,
            implode(', ', $setParts)
        );

        $params = [];
        [$whereClause, $params, $values] = $this->buildWhereClause($filters, $params, $values);

        if ($whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        }

        $this->executeStatement($sql, $params, $values);

        // Fetch and return updated records
        return $this->executeSelect($table, $filters, null, null, null, null);
    }

    /**
     * Execute a DELETE query and return the deleted records.
     *
     * First fetches the matching records, then deletes them, and returns
     * the previously-fetched data so callers receive the deleted records.
     *
     * @param string $table   Table name (already snake_case)
     * @param array  $filters Filter conditions
     * @return array          Array of deleted records
     */
    private function executeDelete(string $table, array $filters): array
    {
        // Fetch records before deleting so we can return them
        $deleted = $this->executeSelect($table, $filters, null, null, null, null);

        $sql = "DELETE FROM `{$table}`";
        $params = [];
        $values = [];

        [$whereClause, $params, $values] = $this->buildWhereClause($filters, $params, $values);

        if ($whereClause !== '') {
            $sql .= ' WHERE ' . $whereClause;
        } else {
            throw new \RuntimeException('DELETE requires filters to prevent accidental full-table deletion.');
        }

        $this->executeStatement($sql, $params, $values);

        return $deleted;
    }

    // ---------------------------------------------------------------------------
    // Query building helpers
    // ---------------------------------------------------------------------------

    /**
     * Build a WHERE clause from the filters array.
     *
     * @param array $filters  Filter conditions: [column => [operator => value]]
     * @param array $params   Reference to params array (for named params, unused — we use positional)
     * @param array $values   Reference to values array for binding
     * @return array          [whereClause string, params array, values array]
     */
    private function buildWhereClause(array $filters, array $params, array $values): array
    {
        $clauses = [];

        foreach ($filters as $column => $conditions) {
            $snakeColumn = $this->toSnakeCase((string) $column);

            // Support shorthand: if conditions is not an array, treat as ['eq' => $conditions]
            if (!is_array($conditions)) {
                $conditions = ['eq' => $conditions];
            }

            foreach ($conditions as $operator => $value) {
                $clause = $this->buildFilterExpression($snakeColumn, $operator, $value, $values);
                if ($clause !== null) {
                    $clauses[] = $clause;
                }
            }
        }

        $whereClause = !empty($clauses) ? implode(' AND ', $clauses) : '';

        return [$whereClause, $params, $values];
    }

    /**
     * Build a single filter expression for a column + operator + value.
     *
     * @param string $column   Column name (snake_case, backtick-quoted)
     * @param string $operator Operator (eq, neq, gt, lt, gte, lte, like, ilike, in, not_in, is, is_not, not_null, null)
     * @param mixed  $value    Filter value
     * @param array  $values   Reference to values array for binding
     * @return string|null     SQL expression or null
     */
    private function buildFilterExpression(string $column, string $operator, $value, array &$values): ?string
    {
        $quotedCol = '`' . $column . '`';

        switch ($operator) {
            case 'eq':
                $values[] = $value;
                return $quotedCol . ' = ?';

            case 'neq':
                $values[] = $value;
                return $quotedCol . ' != ?';

            case 'gt':
                $values[] = $value;
                return $quotedCol . ' > ?';

            case 'lt':
                $values[] = $value;
                return $quotedCol . ' < ?';

            case 'gte':
                $values[] = $value;
                return $quotedCol . ' >= ?';

            case 'lte':
                $values[] = $value;
                return $quotedCol . ' <= ?';

            case 'like':
            case 'ilike':
                $values[] = $value;
                return $quotedCol . ' LIKE ?';

            case 'in':
                if (!is_array($value) || empty($value)) {
                    return null;
                }
                $placeholders = array_fill(0, count($value), '?');
                foreach ($value as $v) {
                    $values[] = $v;
                }
                return $quotedCol . ' IN (' . implode(', ', $placeholders) . ')';

            case 'not_in':
                if (!is_array($value) || empty($value)) {
                    return null;
                }
                $placeholders = array_fill(0, count($value), '?');
                foreach ($value as $v) {
                    $values[] = $v;
                }
                return $quotedCol . ' NOT IN (' . implode(', ', $placeholders) . ')';

            case 'is':
                return $quotedCol . ' IS ' . $this->toSqlValue($value);

            case 'is_not':
                return $quotedCol . ' IS NOT ' . $this->toSqlValue($value);

            case 'not_null':
                return $quotedCol . ' IS NOT NULL';

            case 'null':
                return $quotedCol . ' IS NULL';

            default:
                throw new \RuntimeException("Unsupported filter operator: {$operator}");
        }
    }

    /**
     * Convert a PHP value to its SQL literal representation for IS / IS NOT.
     *
     * @param mixed $value PHP value
     * @return string      SQL literal
     */
    private function toSqlValue($value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (strtolower((string) $value) === 'null') {
            return 'NULL';
        }

        if (strtolower((string) $value) === 'true') {
            return 'TRUE';
        }

        if (strtolower((string) $value) === 'false') {
            return 'FALSE';
        }

        // String value — quote and escape for safety
        return $this->pdo->quote((string) $value);
    }

    /**
     * Build an ORDER BY clause from a Supabase-style order string.
     *
     * @param string $order Order string, e.g. "created_at.desc" or "name.asc"
     * @return string       SQL ORDER BY clause
     */
    private function buildOrderByClause(string $order): string
    {
        $parts = [];

        // Support multiple order columns separated by commas
        $orderSegments = explode(',', $order);

        foreach ($orderSegments as $segment) {
            $segment = trim($segment);
            if ($segment === '') {
                continue;
            }

            $pieces = explode('.', $segment, 2);
            $column = $this->toSnakeCase($pieces[0]);
            $direction = strtoupper($pieces[1] ?? 'ASC');

            if (!in_array($direction, ['ASC', 'DESC'], true)) {
                $direction = 'ASC';
            }

            $parts[] = '`' . $column . '` ' . $direction;
        }

        if (empty($parts)) {
            return '';
        }

        return ' ORDER BY ' . implode(', ', $parts);
    }

    /**
     * Execute a prepared statement with positional parameters.
     *
     * @param string $sql    SQL query with ? placeholders
     * @param array  $params Unused (for API compatibility)
     * @param array  $values Values to bind
     * @return \PDOStatement
     * @throws \RuntimeException
     */
    private function executeStatement(string $sql, array $params, array $values): \PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            foreach ($values as $i => $value) {
                $paramIndex = $i + 1;
                $stmt->bindValue($paramIndex, $value, \PDO::PARAM_STR);
            }

            $stmt->execute();

            return $stmt;
        } catch (\PDOException $e) {
            throw new \RuntimeException(
                'Query Error [' . $e->getCode() . ']: ' . $e->getMessage() . ' — SQL: ' . $sql,
                (int) $e->getCode(),
                $e
            );
        }
    }

    // ---------------------------------------------------------------------------
    // Case conversion helpers
    // ---------------------------------------------------------------------------

    /**
     * Recursively convert all array keys from snake_case to camelCase.
     *
     * @param array $arr Array with snake_case keys
     * @return array     Array with camelCase keys
     */
    private function arrayKeysToCamelCase(array $arr): array
    {
        $result = [];
        foreach ($arr as $key => $value) {
            $camelKey = $this->toCamelCase((string) $key);
            $result[$camelKey] = is_array($value)
                ? $this->arrayKeysToCamelCase($value)
                : $value;
        }
        return $result;
    }

    /**
     * Recursively convert all array keys from camelCase to snake_case.
     *
     * @param array $arr Array with camelCase keys
     * @return array     Array with snake_case keys
     */
    private function arrayKeysToSnakeCase(array $arr): array
    {
        $result = [];
        foreach ($arr as $key => $value) {
            $snakeKey = $this->toSnakeCase((string) $key);
            $result[$snakeKey] = is_array($value)
                ? $this->arrayKeysToSnakeCase($value)
                : $value;
        }
        return $result;
    }
}
