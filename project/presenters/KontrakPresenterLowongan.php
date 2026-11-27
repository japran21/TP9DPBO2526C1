<?php

require_once __DIR__ . '/KontrakPresenter.php';

/**
 * Kontrak Presenter untuk modul CRUD Lowongan.
 */
interface KontrakPresenterLowongan extends KontrakPresenter
{
    public function tampilkanLowongan(string $flashMessage = '', string $flashType = 'success'): string;

    public function tampilkanFormLowongan(?int $id = null): string;

    public function tambahLowongan(
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool;

    public function ubahLowongan(
        int $id,
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool;

    public function hapusLowongan(int $id): bool;
}
