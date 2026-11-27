<?php

require_once __DIR__ . '/KontrakModel.php';
require_once __DIR__ . '/Lowongan.php';

/**
 * Kontrak Model untuk modul CRUD Lowongan.
 */
interface KontrakModelLowongan extends KontrakModel
{
    /**
     * @return array<int, Lowongan>
     */
    public function getAllLowongan(): array;

    public function getLowonganById(int $id): ?Lowongan;

    public function addLowongan(
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool;

    public function updateLowongan(
        int $id,
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool;

    public function deleteLowongan(int $id): bool;
}
