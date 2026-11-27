<?php
class DB
{
    /** @var PDO|null */
    protected ?PDO $conn = null;
    public function __construct(
        string $host = 'localhost',
        string $dbName = 'mvp_kandidat_db',
        string $username = 'root',
        string $password = '',
        ?PDO $pdo = null
    ) {
        if ($pdo !== null) {
            $this->conn = $pdo;
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return;
        }

        $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";
        $this->conn = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    /**
     * Mengambil seluruh baris hasil query SELECT menggunakan prepared statement.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function fetchAll(string $query, array $params = []): array
    {
        $stmt = $this->requireConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil satu baris hasil query SELECT menggunakan prepared statement.
     *
     * @return array<string, mixed>|null
     */
    protected function fetchOne(string $query, array $params = []): ?array
    {
        $stmt = $this->requireConnection()->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /**
     * Menjalankan query INSERT atau UPDATE menggunakan prepared statement.
     */
    protected function executeStatement(string $query, array $params = []): bool
    {
        $stmt = $this->requireConnection()->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Menjalankan query DELETE menggunakan prepared statement dan memastikan baris terhapus.
     */
    protected function executeDelete(string $query, array $params = []): bool
    {
        $stmt = $this->requireConnection()->prepare($query);
        if (!$stmt->execute($params)) {
            return false;
        }

        return $stmt->rowCount() > 0;
    }

    /**
     * Menutup koneksi database secara eksplisit.
     */
    public function close(): void
    {
        $this->conn = null;
    }

    private function requireConnection(): PDO
    {
        if ($this->conn === null) {
            throw new RuntimeException('Koneksi database sudah ditutup.');
        }

        return $this->conn;
    }
}