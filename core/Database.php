<?php

declare(strict_types=1);

namespace Core;

/**
 * Supabase REST API Client
 *
 * Communicates with the Supabase PostgREST API using PHP cURL.
 * Supports full CRUD operations with filtering, ordering, pagination,
 * and column selection. Handles snake_case ↔ camelCase conversion.
 */
class Database
{
    /** @var string Supabase project URL */
    private string $baseUrl;

    /** @var string API key (service_role key for admin access, bypasses RLS) */
    private string $apiKey;

    /** @var int Default timeout for cURL requests in seconds */
    private int $timeout = 30;

    /**
     * Create a new Database instance.
     *
     * @param string $supabaseUrl  Full Supabase project URL (e.g. https://xxx.supabase.co)
     * @param string $apiKey       Service role API key (secret key)
     */
    public function __construct(string $supabaseUrl, string $apiKey)
    {
        $this->baseUrl = rtrim($supabaseUrl, '/');
        $this->apiKey = $apiKey;
    }

    /**
     * Execute a query against the Supabase REST API.
     *
     * @param string      $table   Table name in the database
     * @param string      $method  HTTP method: GET, POST, PATCH, DELETE
     * @param array|null  $data    Data payload for POST/PATCH requests
     * @param array       $filters Associative array of filters: [column => [operator => value]]
     * @param string|null $order   Ordering string, e.g. "created_at.desc" or "name.asc"
     * @param int|null    $limit   Maximum number of records to return
     * @param int|null    $offset  Number of records to skip
     * @param string|null $select  Comma-separated list of columns to select
     * @return array              Response data as associative array
     * @throws \RuntimeException  On cURL or API errors
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
        $url = $this->baseUrl . '/rest/v1/' . $this->toSnakeCase($table);

        // Build query string for GET requests
        $queryParams = [];

        // Apply filters
        foreach ($filters as $column => $conditions) {
            $snakeColumn = $this->toSnakeCase($column);
            foreach ($conditions as $operator => $value) {
                $queryParams[] = $snakeColumn . '=' . $operator . '.' . urlencode((string) $value);
            }
        }

        // Apply ordering
        if ($order !== null) {
            $queryParams[] = 'order=' . $this->toSnakeCase($order);
        }

        // Apply pagination
        if ($limit !== null && $offset !== null) {
            $queryParams[] = 'limit=' . $limit;
            $queryParams[] = 'offset=' . $offset;
        } elseif ($limit !== null) {
            $queryParams[] = 'limit=' . $limit;
        } elseif ($offset !== null) {
            $queryParams[] = 'offset=' . $offset;
        }

        // Apply column selection
        if ($select !== null) {
            // Convert camelCase columns in select to snake_case
            $columns = array_map([$this, 'toSnakeCase'], explode(',', $select));
            $queryParams[] = 'select=' . implode(',', $columns);
        }

        if (!empty($queryParams)) {
            $url .= '?' . implode('&', $queryParams);
        }

        // Prepare cURL
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $this->buildHeaders($method),
        ]);

        // Attach body for POST/PATCH
        if ($data !== null && in_array($method, ['POST', 'PATCH', 'DELETE'], true)) {
            $snakeData = $this->arrayKeysToSnakeCase($data);
            $jsonBody = json_encode($snakeData, JSON_THROW_ON_ERROR);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new \RuntimeException('cURL Error: ' . $curlError);
        }

        $decoded = json_decode($response ?: '[]', true);
        if (!is_array($decoded)) {
            $decoded = ['raw' => $response];
        }

        // Supabase returns an error object with "message" on failures
        if (isset($decoded['message']) && $httpCode >= 400) {
            throw new \RuntimeException('Supabase Error [' . $httpCode . ']: ' . $decoded['message']);
        }

        // Convert all keys to camelCase
        return $this->arrayKeysToCamelCase($decoded);
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
     * @param string $table   Table name
     * @param mixed  $id      Primary key value
     * @param string $idColumn Name of the primary key column (default: id)
     * @return array|null     Record or null
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
     * @return array        Inserted record (with representation)
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
        // Supabase returns Content-Range header with total count
        $url = $this->baseUrl . '/rest/v1/' . $this->toSnakeCase($table);

        $queryParams = [];
        foreach ($filters as $column => $conditions) {
            $snakeColumn = $this->toSnakeCase($column);
            foreach ($conditions as $operator => $value) {
                $queryParams[] = $snakeColumn . '=' . $operator . '.' . urlencode((string) $value);
            }
        }

        if (!empty($queryParams)) {
            $url .= '?' . implode('&', $queryParams);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CUSTOMREQUEST  => 'HEAD',
            CURLOPT_HTTPHEADER     => [
                'apikey: ' . $this->apiKey,
                'Authorization: Bearer ' . $this->apiKey,
                'Prefer: count=exact',
                'Range: 0-0',
            ],
            CURLOPT_NOBODY         => true,
            CURLOPT_HEADER         => true,
        ]);

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $headers = substr($response ?: '', 0, $headerSize);

        // Extract content-range header
        if (preg_match('/content-range:\s*0-0\/(\d+)/i', $headers, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    /**
     * Execute a raw Supabase RPC (PostgreSQL function call).
     *
     * @param string $functionName RPC function name
     * @param array  $params      Parameters to pass
     * @return array              Function result
     */
    public function rpc(string $functionName, array $params = []): array
    {
        $url = $this->baseUrl . '/rest/v1/rpc/' . $functionName;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'apikey: ' . $this->apiKey,
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'Prefer: return=representation',
            ],
            CURLOPT_POSTFIELDS     => json_encode($params, JSON_THROW_ON_ERROR),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response ?: '[]', true);
        if (!is_array($decoded)) {
            $decoded = ['raw' => $response];
        }

        if (isset($decoded['message']) && $httpCode >= 400) {
            throw new \RuntimeException('RPC Error [' . $httpCode . ']: ' . $decoded['message']);
        }

        return $this->arrayKeysToCamelCase($decoded);
    }

    /**
     * Build HTTP headers for the API request.
     *
     * @param string $method HTTP method
     * @return array        Array of header strings
     */
    private function buildHeaders(string $method): array
    {
        $headers = [
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ];

        // For mutations, request the server to return the affected rows
        if (in_array($method, ['POST', 'PATCH', 'DELETE'], true)) {
            $headers[] = 'Prefer: return=representation';
        }

        return $headers;
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
