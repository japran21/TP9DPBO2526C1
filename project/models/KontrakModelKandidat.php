<?php

require_once __DIR__ . '/KontrakModel.php';
require_once __DIR__ . '/Kandidat.php';

/**
 * Kontrak Model untuk modul CRUD Kandidat.
 */
interface KontrakModelKandidat extends KontrakModel
{
    /**
     * @return array<int, Kandidat>
     */
    public function getAllKandidat(): array;

    public function getKandidatById(int $id): ?Kandidat;

    public function addKandidat(
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool;

    public function updateKandidat(
        int $id,
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool;

    public function deleteKandidat(int $id): bool;
}
