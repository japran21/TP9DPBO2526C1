<?php

require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/Lowongan.php';
require_once __DIR__ . '/KontrakModelLowongan.php';

/**
 * Model akses data untuk tabel `lowongan` menggunakan prepared statement.
 */
class TabelLowongan extends DB implements KontrakModelLowongan
{
    /**
     * Mengambil seluruh data lowongan diurutkan berdasarkan ID menaik.
     *
     * @return array<int, Lowongan>
     */
    public function getAllLowongan(): array
    {
        $rows = $this->fetchAll(
            'SELECT id, judulPosisi, departemen, lokasi, tipeKerja, kuota, status FROM lowongan ORDER BY id ASC'
        );

        $daftarLowongan = [];
        foreach ($rows as $row) {
            $daftarLowongan[] = $this->mapRowToEntity($row);
        }

        return $daftarLowongan;
    }

    /**
     * Mengambil satu data lowongan berdasarkan ID.
     */
    public function getLowonganById(int $id): ?Lowongan
    {
        $row = $this->fetchOne(
            'SELECT id, judulPosisi, departemen, lokasi, tipeKerja, kuota, status FROM lowongan WHERE id = :id',
            [':id' => $id]
        );

        return $row === null ? null : $this->mapRowToEntity($row);
    }

    /**
     * Menambahkan data lowongan baru menggunakan prepared statement.
     */
    public function addLowongan(
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool {
        return $this->executeStatement(
            'INSERT INTO lowongan (judulPosisi, departemen, lokasi, tipeKerja, kuota, status)
             VALUES (:judulPosisi, :departemen, :lokasi, :tipeKerja, :kuota, :status)',
            [
                ':judulPosisi' => $judulPosisi,
                ':departemen' => $departemen,
                ':lokasi' => $lokasi,
                ':tipeKerja' => $tipeKerja,
                ':kuota' => $kuota,
                ':status' => $status,
            ]
        );
    }

    /**
     * Memperbarui data lowongan berdasarkan ID menggunakan prepared statement.
     */
    public function updateLowongan(
        int $id,
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool {
        return $this->executeStatement(
            'UPDATE lowongan
             SET judulPosisi = :judulPosisi,
                 departemen = :departemen,
                 lokasi = :lokasi,
                 tipeKerja = :tipeKerja,
                 kuota = :kuota,
                 status = :status
             WHERE id = :id',
            [
                ':id' => $id,
                ':judulPosisi' => $judulPosisi,
                ':departemen' => $departemen,
                ':lokasi' => $lokasi,
                ':tipeKerja' => $tipeKerja,
                ':kuota' => $kuota,
                ':status' => $status,
            ]
        );
    }

    /**
     * Menghapus data lowongan berdasarkan ID menggunakan prepared statement.
     */
    public function deleteLowongan(int $id): bool
    {
        return $this->executeDelete(
            'DELETE FROM lowongan WHERE id = :id',
            [':id' => $id]
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToEntity(array $row): Lowongan
    {
        return new Lowongan(
            (int) $row['id'],
            (string) $row['judulPosisi'],
            (string) $row['departemen'],
            (string) $row['lokasi'],
            (string) $row['tipeKerja'],
            (int) $row['kuota'],
            (string) $row['status']
        );
    }
}
