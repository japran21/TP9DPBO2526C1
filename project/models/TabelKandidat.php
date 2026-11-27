<?php

require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/Kandidat.php';
require_once __DIR__ . '/KontrakModelKandidat.php';

/**
 * Model akses data untuk tabel `kandidat` menggunakan prepared statement.
 */
class TabelKandidat extends DB implements KontrakModelKandidat
{
    /**
     * Mengambil seluruh data kandidat diurutkan berdasarkan ID menaik.
     *
     * @return array<int, Kandidat>
     */
    public function getAllKandidat(): array
    {
        $rows = $this->fetchAll(
            'SELECT id, nama, posisi, domisili, skorTes, pengalamanTahun FROM kandidat ORDER BY id ASC'
        );

        $daftarKandidat = [];
        foreach ($rows as $row) {
            $daftarKandidat[] = $this->mapRowToEntity($row);
        }

        return $daftarKandidat;
    }

    /**
     * Mengambil satu data kandidat berdasarkan ID.
     */
    public function getKandidatById(int $id): ?Kandidat
    {
        $row = $this->fetchOne(
            'SELECT id, nama, posisi, domisili, skorTes, pengalamanTahun FROM kandidat WHERE id = :id',
            [':id' => $id]
        );

        return $row === null ? null : $this->mapRowToEntity($row);
    }

    /**
     * Menambahkan data kandidat baru menggunakan prepared statement.
     */
    public function addKandidat(
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool {
        return $this->executeStatement(
            'INSERT INTO kandidat (nama, posisi, domisili, skorTes, pengalamanTahun)
             VALUES (:nama, :posisi, :domisili, :skorTes, :pengalamanTahun)',
            [
                ':nama' => $nama,
                ':posisi' => $posisi,
                ':domisili' => $domisili,
                ':skorTes' => $skorTes,
                ':pengalamanTahun' => $pengalamanTahun,
            ]
        );
    }

    /**
     * Memperbarui data kandidat berdasarkan ID menggunakan prepared statement.
     */
    public function updateKandidat(
        int $id,
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool {
        return $this->executeStatement(
            'UPDATE kandidat
             SET nama = :nama,
                 posisi = :posisi,
                 domisili = :domisili,
                 skorTes = :skorTes,
                 pengalamanTahun = :pengalamanTahun
             WHERE id = :id',
            [
                ':id' => $id,
                ':nama' => $nama,
                ':posisi' => $posisi,
                ':domisili' => $domisili,
                ':skorTes' => $skorTes,
                ':pengalamanTahun' => $pengalamanTahun,
            ]
        );
    }

    /**
     * Menghapus data kandidat berdasarkan ID menggunakan prepared statement.
     */
    public function deleteKandidat(int $id): bool
    {
        return $this->executeDelete(
            'DELETE FROM kandidat WHERE id = :id',
            [':id' => $id]
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapRowToEntity(array $row): Kandidat
    {
        return new Kandidat(
            (int) $row['id'],
            (string) $row['nama'],
            (string) $row['posisi'],
            (string) $row['domisili'],
            (int) $row['skorTes'],
            (int) $row['pengalamanTahun']
        );
    }
}
